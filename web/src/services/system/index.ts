/**
 * 系统服务接口
 */
import { request } from '@umijs/max';

/**
 * 获取系统字典
 */
export const gitDict = () => {
  return request<API.ResponseStructure<any>>('/system/dict/list', {
    method: 'get',
  })
}
