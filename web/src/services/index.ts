import { request } from '@umijs/max';

/**
 * 管理端用户登录
 * @param data
 * @constructor
 */
export async function adminLogin(data: USER.UserLoginFrom) {
  return request<USER.LoginResult>('/admin/login', {
    method: 'post',
    data
  });
}

/**
 * 获取管理员用户信息
 * @constructor
 */
export async function getAdminInfo() {
  return request<USER.AdminInfoResult>('/admin/info', {
    method: 'get'
  });
}

/**
 * 退出登录
 * @constructor
 */
export async function logout() {
  return request<API.ResponseStructure<any>>('/admin/logout', {
    method: 'post'
  });
}




