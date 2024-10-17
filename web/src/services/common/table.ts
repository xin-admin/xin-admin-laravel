/**
 * Xin Table 公共接口
 */
import { request } from '@umijs/max';
interface XinApi {
   (
      url: string,
      params?: {
        keyword?: string;
        current?: number;
        pageSize?: number;
      } | { [key: string]: any },
      data?: { [key: string]: any },
      options?: { [key: string]: any }
    ): Promise<API.ResponseStructure<any>>
}

let app = '/admin'

/**
 * 公共查询接口
 * @param url
 * @param params
 * @param options
 */
export const listApi: XinApi = (url,params,options) =>  {
  return request<API.ResponseStructure<any>>(app+ url, {
    method: 'GET',
    params: {
      ...params,
    },
    ...(options || {}),
  });
}

/**
 * 公共新增接口
 * @param url
 * @param data
 * @param options
 */
export const addApi: XinApi = (url,data,options) => {
  return request<API.ResponseStructure<any>>(app+ url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    data: data,
    ...(options || {}),
  });
}

/**
 * 公共编辑接口
 * @param url
 * @param data
 * @param options
 */
export const editApi: XinApi = (url,data,options) =>  {
  return request<API.ResponseStructure<any>>(app+ url, {
    method: 'PUT',
    data: { ...data },
    ...(options || {}),
  });
}

/**
 * 公共删除接口
 * @param url
 * @param params
 * @param options
 */
export const deleteApi: XinApi = (url,params,options) => {
  return request<API.ResponseStructure<any>>(app+ url, {
    method: 'DELETE',
    params: { ...params },
    ...(options || {}),
  });
}