/** Xin Table 公共接口 */
import createAxios from '@/utils/request';
import type { AxiosRequestConfig } from "axios";

type IListParams = {
  keyword?: string;
  current?: number;
  pageSize?: number;
} & { [key: string]: unknown }

/**
 * 查询详情接口
 * @param url
 * @param id
 * @param options
 */
export function Get<T,>(
    url: string,
    id: number | string,
    options?: AxiosRequestConfig | undefined
){
  return createAxios<T>({
    url: url + '/' + id,
    method: 'get',
    ...(options || {}),
  })
}

/**
 * 公共查询接口
 * @param url
 * @param params
 * @param options
 */
export function List<T,>(
    url: string,
    params?: IListParams,
    options?: AxiosRequestConfig | undefined
){
  return createAxios<API.ListResponse<T>>({
    url: url,
    method: 'get',
    params,
    ...(options || {}),
  })
}

/**
 * 公共新增接口
 * @param url
 * @param data
 * @param options
 */
export function Create<T = API.ResponseStructure, Values = any>(
    url: string,
    data?: Values,
    options?: AxiosRequestConfig | undefined
){
  return createAxios<T>({
    url: url,
    method: 'post',
    data,
    ...(options || {}),
  })
}

/**
 * 公共编辑接口
 * @param url
 * @param data
 * @param options
 */
export function Update<T = API.ResponseStructure, Values = any>(
    url: string,
    data?: Values,
    options?: AxiosRequestConfig | undefined
){
  return createAxios<T>({
    url: url,
    method: 'put',
    data,
    ...(options || {}),
  })
}

/**
 * 公共删除接口
 * @param url
 * @param params
 * @param options
 */
export function Delete<T,>(
    url: string,
    params?: { [key: string]: unknown },
    options?: AxiosRequestConfig | undefined
){
  return createAxios<T>({
    url: url,
    method: 'delete',
    params,
    ...(options || {}),
  })
}
