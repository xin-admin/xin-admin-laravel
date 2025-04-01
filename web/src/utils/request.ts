import type { RequestConfig } from '@umijs/max';
import { history, request } from '@umijs/max';
import { message, notification } from 'antd';

/** 错误处理方案： 错误类型 */
enum ErrorShowType {
  SUCCESS_MESSAGE = 0,      // 成功消息
  WARN_MESSAGE = 1,         // 警告消息
  ERROR_MESSAGE = 2,        // 失败消息
  SUCCESS_NOTIFICATION = 3, // 成功通知
  WARN_NOTIFICATION = 4,    // 警告通知
  ERROR_NOTIFICATION = 5,   // 失败通知
  SILENT = 99,              // 无状态
}

/**
 * 错误处理
 * 根据 showType 类型进行错误处理，后端可以通过 showType 来设置不同的错误处理方案。
 * @param data
 */
const errorHandler = async (data: API.ResponseStructure<any>) => {
  let { msg = '', showType = 0, description = '', placement = 'topRight' } = data;
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
        placement
      });
      break;
    case ErrorShowType.WARN_NOTIFICATION:
      notification.warning({
        description: description,
        message: msg,
        placement
      });
      break;
    case ErrorShowType.ERROR_NOTIFICATION:
      notification.error({
        description: description,
        message: msg,
        placement
      });
      break;
    default:
      message.error(msg);
  }
}

/**
 * 全局请求配置
 */
const requestConfig: RequestConfig = {
  baseURL: process.env.DOMAIN,
  timeout: 5000,
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
  // 请求拦截器
  requestInterceptors: [
    (config: any) => {
      let token = localStorage.getItem('token');
      if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
      }
      return { ...config };
    },
  ],
  responseInterceptors: [
    [
      async (response) => {
        const { data = {} as any } = response;
        if(data.success) return Promise.resolve(response);
        await errorHandler(data);
        return Promise.reject(response);
      },
      async (error: any) => {
        const {response} = error;
        if(!response) {
          message.error('网路请求错误！');
          return Promise.reject(response);
        }
        if(response.status === 401 && location.pathname != '/login') {
          message.error(`请先登录！`);
          localStorage.clear();
          history.push('/login');
          return Promise.reject(response);
        }
        if(response.data) {
          await errorHandler(response.data);
        }else {
          message.error(error.message);
        }
        return Promise.reject(response);
      }
    ]
  ]
};

export default requestConfig;
