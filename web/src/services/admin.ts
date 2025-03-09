import { request } from '@umijs/max';

/**
 * 更新用户信息
 */
export async function updateAdmin(data: any) {
  return request<API.ResponseStructure<any>>('/admin', {
    method: 'put',
    data
  });
}
