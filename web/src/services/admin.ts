import { request } from '@umijs/max';

const api = {
  updateAdmin: '/admin/admin/updateAdmin'
}


/**
 * 更新用户信息
 */
export async function updateAdmin(data: any) {
  return request<API.ResponseStructure<any>>(api.updateAdmin, {
    method: 'put',
    data
  });
}
