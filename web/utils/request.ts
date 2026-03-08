import axios, {type AxiosRequestConfig, type AxiosResponse} from "axios";
import type {AxiosInstance} from "axios";
import type {NotificationArgsProps} from "antd";

// 全局变量声明
window.requests = [];
window.tokenRefreshing = false;

// 请求去重映射表
const pendingMap = new Map<string, AbortController>();

// 配置常量
const REQUEST_TIMEOUT = 10000; // 请求超时时间
const LOGIN_PATH = '/login'; // 登录页面路径

/**
 * 添加请求到待处理映射表，实现请求去重
 * @param config - Axios 请求配置
 */
function addPending(config: AxiosRequestConfig): void {
  const pendingKey = getPendingKey(config);
  
  if (!config.signal) {
    const controller = new AbortController();
    config.signal = controller.signal;
    
    // 只在不存在时添加，避免重复请求
    if (!pendingMap.has(pendingKey)) {
      pendingMap.set(pendingKey, controller);
    }
  }
}

/**
 * 移除待处理的请求，取消重复请求
 * @param config - Axios 请求配置
 */
function removePending(config: AxiosRequestConfig): void {
  const pendingKey = getPendingKey(config);
  
  if (pendingMap.has(pendingKey)) {
    const controller = pendingMap.get(pendingKey);
    controller?.abort();
    pendingMap.delete(pendingKey);
  }
}

/**
 * 生成每个请求的唯一标识键
 * @param config - Axios 请求配置
 * @returns 请求的唯一标识字符串
 */
function getPendingKey(config: AxiosRequestConfig): string {
  let { data } = config;
  const { url, method, params, headers } = config;
  
  // 处理响应中返回的字符串格式 data
  if (typeof data === 'string') {
    try {
      data = JSON.parse(data);
    } catch (e) {
      // 如果解析失败，保持原值
      console.warn('Failed to parse request data:', e);
    }
  }
  
  // 安全获取 header 值
  const headersObj = headers as Record<string, any> || {};
  const language = headersObj['User-Language'] || headersObj['i18nextLng'] || '';
  const auth = headersObj['Authorization'] || '';
  
  return [
    url || '',
    method || '',
    language,
    auth,
    JSON.stringify(params || {}),
    JSON.stringify(data || {}),
  ].join('&');
}

/**
 * 处理网络错误（HTTP 状态码错误）
 * @param errStatus - HTTP 状态码
 */
const handleNetworkError = async (errStatus?: number): Promise<void> => {
  if (!errStatus) {
    window.$message?.error("无法连接到服务器！");
    return;
  }

  // 401 未授权 - 需要特殊处理，清除认证信息并跳转登录
  if (errStatus === 401) {
    window.$message?.error('您未登录，或者登录已经超时，请先登录！');
    
    // 清除本地存储的认证信息
    localStorage.removeItem('token');
    localStorage.removeItem('refresh_token');
    localStorage.removeItem('auth-storage');
    
    // 避免在登录页重复跳转
    if (window.location.pathname !== LOGIN_PATH) {
      window.location.href = LOGIN_PATH;
    }
    return;
  }

  // HTTP 状态码错误消息映射表
  const errorMessages: Record<number, string> = {
    302: '接口重定向了！',
    400: '参数不正确！',
    403: '您没有权限操作！',
    404: '请求错误，未找到该资源！',
    408: '请求超时！',
    409: '系统已存在相同数据！',
    500: '服务器内部错误！',
    501: '服务未实现！',
    502: '网关错误！',
    503: '服务不可用！',
    504: '服务暂时无法访问，请稍后再试！',
    505: 'HTTP版本不受支持！',
  };

  const messageString = errorMessages[errStatus] || `连接错误 (状态码: ${errStatus})`;
  window.$message?.error(messageString);
};

/**
 * 处理业务错误（接口返回的业务逻辑错误）
 * @param data - 接口返回的数据结构
 */
const handleBusinessError = async (data: API.ResponseStructure<any>): Promise<void> => {
  const { msg = '', showType = 0, description = '', placement = 'topRight' } = data;
  
  // 通知配置
  const notificationProps: NotificationArgsProps = {
    message: msg,
    description: description,
    placement: placement
  };
  
  // 根据 showType 展示不同类型的提示
  // 0-2: Message 提示; 3-5: Notification 通知; 99: 静默处理
  switch (showType) {
    case 99: // 静默处理，不显示任何提示
      break;
    case 0: // Message 成功提示
      if (msg) window.$message?.success(msg);
      break;
    case 1: // Message 警告提示
      if (msg) window.$message?.warning(msg);
      break;
    case 2: // Message 错误提示
      if (msg) window.$message?.error(msg);
      break;
    case 3: // Notification 成功通知
      if (msg) window.$notification?.success(notificationProps);
      break;
    case 4: // Notification 警告通知
      if (msg) window.$notification?.warning(notificationProps);
      break;
    case 5: // Notification 错误通知
      if (msg) window.$notification?.error(notificationProps);
      break;
    default: // 默认使用 Message 错误提示
      if (msg) window.$message?.error(msg);
  }
};

/**
 * 创建并配置 Axios 实例
 * @param axiosConfig - Axios 请求配置
 * @returns Promise<AxiosResponse<T>>
 */
function createAxios<Data, T = API.ResponseStructure<Data>>(
  axiosConfig: AxiosRequestConfig
): Promise<AxiosResponse<T>> {
  // 创建 axios 实例
  const instance: AxiosInstance = axios.create({
    baseURL: import.meta.env.VITE_BASE_URL || '',
    timeout: REQUEST_TIMEOUT,
    responseType: 'json',
    // 允许携带凭证
    withCredentials: false,
  });

  // ==================== 请求拦截器 ====================
  instance.interceptors.request.use(
    (config) => {
      // 请求去重：移除重复的待处理请求
      removePending(config);
      // 将当前请求添加到待处理映射表
      addPending(config);

      // 确保 headers 存在
      config.headers = config.headers || {};

      // 自动附加国际化语言到请求头
      const currentLanguage = localStorage.getItem('i18nextLng');
      if (currentLanguage) {
        config.headers['User-Language'] = currentLanguage;
      }

      // 自动附加 Token 到请求头（排除登录接口）
      const token = localStorage.getItem('token');
      if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
      }

      return config;
    },
    (error) => {
      // 请求错误处理
      console.error('Request Error:', error);
      return Promise.reject(error);
    }
  );

  // ==================== 响应拦截器 ====================
  instance.interceptors.response.use(
    async (response) => {
      // 请求完成后，从待处理映射表中移除
      removePending(response.config);
      
      const { data } = response;
      
      // 业务成功，直接返回
      if (data?.success) {
        return Promise.resolve(response);
      }
      
      // 业务失败，统一处理业务错误
      await handleBusinessError(data);
      return Promise.reject(response);
    },
    async (err) => {
      // 请求被取消（去重或手动取消）
      if (axios.isCancel(err)) {
        console.warn('请求已取消:', err.message);
        return Promise.reject(err);
      }
      
      // 有响应但状态码错误（4xx, 5xx）
      if (err.response) {
        removePending(err.response.config);
        await handleNetworkError(err.response.status);
        return Promise.reject(err.response);
      }
      
      // 网络错误或超时（无响应）
      if (err.code === 'ECONNABORTED') {
        window.$message?.error('请求超时，请稍后重试！');
      } else if (err.message?.includes('Network Error')) {
        window.$message?.error('网络连接失败，请检查网络！');
      } else {
        window.$message?.error(`网络错误: ${err.message || '未知错误'}`);
      }
      
      return Promise.reject(err);
    }
  );
  
  // 执行请求并返回
  return instance(axiosConfig) as Promise<AxiosResponse<T>>;
}

export default createAxios;
