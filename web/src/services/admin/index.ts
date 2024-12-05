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
 * 刷新 Token
 * @constructor
 */
export async function refreshAdminToken() {
  return request<USER.ReToken>('/admin/refreshToken', {
    method: 'post',
    headers: {
      'x-refresh-token': localStorage.getItem('x-refresh-token') || ''
    }
  });
}

/**
 * 退出登录
 * @constructor
 */
export async function Logout() {
  return request<API.ResponseStructure<any>>('/admin/logout', {
    method: 'post'
  });
}




