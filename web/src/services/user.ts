import { request } from '@umijs/max';

const api = {
  vagueSearchApi: '/admin/user/user/vagueSearch', // 搜索用户
  rechargeApi: '/user/list/recharge'
}

/**
 * 搜索用户
 */
export async function vagueSearchUser(params: {search: string | undefined}) {
  return request<API.TableData<USER.UserInfo>>(api.vagueSearchApi, {
    method: 'get',
    params
  });
}


/**
 * 更新用户余额
 */
export async function recharge(data: any) {
  return request<API.ResponseStructure<any>>(api.rechargeApi, {
    method: 'post',
    data
  });
}
