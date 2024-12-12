import { refreshToken } from '@/services';
import type { RequestConfig } from '@umijs/max';
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
 * 错误处理
 */
const errorHandler = async (data: API.ResponseStructure<any>) => {
  let {
    msg = '',
    showType = 0,
    description = ''
  } = data;
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
}


/**
 * 响应拦截
 */
const responseInterceptors: RequestConfig['responseInterceptors'] = [
  [
    async (response) => {
      const { data = {} as any } = response;
      if(response.status === 202) {
        try {
          let res = await refreshToken()
          localStorage.setItem('x-token', res.data.token);
          response.headers!.xToken = res.data.token;
          // 重新发送请求
          return request(response.config.url!,response.config);
        }catch (e) {
          return Promise.reject(e);
        }
      }
      if(data.success) return Promise.resolve(response);
      await errorHandler(data);
      return Promise.reject(response);
    },
    async (error: any) => {
      const {response} = error;
      if(!response) {
        message.error('网路请求错误！');
        return Promise.reject(error);
      }
      if(response.status === 401) {
        message.error(`请先登录！`);
        history.push('/login');
        return Promise.reject(error);
      }
      if(response.data) {
        await errorHandler(response.data);
      }else {
        message.error(error.message);
      }
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
