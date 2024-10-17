import { request } from '@umijs/max';

const api = {
  index: '/api/index',
  login: '/api/index/login',
  reg: '/api/index/register',
  getMailCodeApi: '/api/index/sendMailCode'
}

/**
 * 获取网站信息
 * @constructor
 */
export async function index() {
  return request<API.ResponseStructure<any>>(api.index, {
    method: 'get'
  });
}

/**
 * 用户登录
 */
export async function login(data: USER.UserLoginFrom) {
  return request<USER.LoginResult>(api.login, {
    method: 'post',
    data
  });
}

/**
 * 用户登录
 */
export async function reg(data: USER.UserLoginFrom) {
  return request<USER.LoginResult>(api.reg, {
    method: 'post',
    data
  });
}
