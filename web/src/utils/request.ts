import { refreshAdminToken } from '@/services/admin';
import { refreshUserToken } from '@/services/api/user';
import type { AxiosResponse, RequestConfig } from '@umijs/max';
import { request, history } from '@umijs/max';
import { message, notification } from 'antd';

/**
 * 错误处理方案： 错误类型
 */
enum ErrorShowType {
  SUCCESS_MESSAGE = 0,
  WARN_MESSAGE = 1,
  ERROR_MESSAGE = 2,
  SUCCESS_NOTIFICATION = 3,
  WARN_NOTIFICATION = 4,
  ERROR_NOTIFICATION = 5,
  SILENT = 99,
}

/**
 * 刷新Token
 * @param response
 */
const refreshToken = async (response: AxiosResponse) => {
  try {
    // 登录状态过期，刷新令牌并重新发起请求
    let app = localStorage.getItem('app');
    if( !app || app === 'api'){
      let res = await refreshUserToken()
      localStorage.setItem('x-user-token', res.data.token);
      response.headers!.xUserToken = res.data.token;
      // 重新发送请求
      return await request(response.config.url!, response.config);
    }else {
      let res = await refreshAdminToken()
      localStorage.setItem('x-token', res.data.token);
      response.headers!.xToken = res.data.token;
      // 重新发送请求
      let data = await request(response.config.url!,response.config);
      return Promise.resolve(data);
    }
  }catch (e) {
    return Promise.reject(e);
  }
}

/**
 * 响应拦截
 */
const responseInterceptors: RequestConfig['responseInterceptors'] = [
  [
    async (response) => {
      const { data = {} as any } = response;
      if(response.status === 202) {
        return await refreshToken(response);
      }
      let {
        success,
        msg = '',
        showType = 0,
        description = ''
      } = data as API.ResponseStructure<any>;
      if(success) return Promise.resolve(response);
      switch (showType) {
        case ErrorShowType.SILENT:
          break;
        case ErrorShowType.SUCCESS_MESSAGE:
          message.success(msg);
          break;
        case ErrorShowType.WARN_MESSAGE:
          message.warning(msg);
          break;
        case ErrorShowType.ERROR_MESSAGE:
          message.error(msg);
          break;
        case ErrorShowType.SUCCESS_NOTIFICATION:
          notification.success({
            description: description,
            message: msg,
          });
          break;
        case ErrorShowType.WARN_NOTIFICATION:
          notification.warning({
            description: description,
            message: msg,
          });
          break;
        case ErrorShowType.ERROR_NOTIFICATION:
          notification.error({
            description: description,
            message: msg,
          });
          break;
        default:
          message.error(msg);
      }
      return Promise.reject(response);
    },
    async (error: any) => {
      if(error.response?.status === 401) {
        message.error(`请先登录！`);
        if(localStorage.getItem('app') === 'admin') {
          history.push('/admin/login');
        }else {
          history.push('/client/login');
        }
        return Promise.reject(error);
      }
      message.error(`Response status:${error.response.status}`);
      return Promise.reject(error);
    }
  ]
]
const requestConfig: RequestConfig = {
  baseURL: process.env.DOMAIN,
  timeout: 5000,
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
  // 请求拦截器
  requestInterceptors: [
    (config: any) => {
      let XToken = localStorage.getItem('x-token');
      let XUserToken = localStorage.getItem('x-user-token');
      if (XToken) {
        config.headers['x-token'] = XToken;
      }
      if (XUserToken) {
        config.headers['x-user-token'] = XUserToken;
      }
      return { ...config };
    },
  ],
  responseInterceptors
};

export default requestConfig;
