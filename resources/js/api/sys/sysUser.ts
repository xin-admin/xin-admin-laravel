import createAxios from '@/utils/request';
import type ISysUser from '@/domain/iSysUser.ts';
import type {IMenus} from "@/domain/iSysRule.ts";
import type ISysLoginRecord from "@/domain/iSysLoginRecord.ts";

export interface LoginParams {
  /** 用户名 */
  username?: string;
  /** 密码 */
  password?: string;
  /** 是否记住我 */
  remember?: boolean;
}

export interface LoginResponse {
  /** token */
  token: string;
}

export interface InfoResponse {
  /** 管理员信息 */
  info: ISysUser;
  /** 管理员权限 */
  access: string[];
}

export interface MenuResponse {
  /** 管理员菜单 */
  menus: IMenus[];
}

export interface InfoParams {
  /** 个人简介 */
  bio: string;
  /** 邮箱 */
  email: string;
  /** 手机号 */
  mobile: string;
  /** 昵称 */
  nickname: string;
  /** 性别 */
  sex: number;
}

export interface PasswordParams {
  /** 新密码 */
  newPassword: string;
  /** 旧密码 */
  oldPassword: string;
  /** 重复新密码 */
  rePassword: string;
}

/** 后台用户登录 */
export async function login(data: LoginParams) {
  return createAxios<LoginResponse>({
    url: '/admin/login',
    method: 'post',
    data,
  });
}

/** 后台用户退出登录 */
export async function logout() {
  return createAxios({
    url: '/admin/logout',
    method: 'post',
  });
}

/** 获取管理员用户信息 */
export async function info() {
  return createAxios<InfoResponse>({
    url: '/admin/info',
    method: 'get',
  });
}

/** 获取管理员用户信息 */
export async function menu() {
  return createAxios<MenuResponse>({
    url: '/admin/menu',
    method: 'get',
  });
}

/** 更改管理员信息 */
export async function updateInfo(info: InfoParams) {
  return createAxios({
    url: '/admin',
    method: 'put',
    data: info,
  })
}

/** 修改管理员密码 */
export async function updatePassword(data: PasswordParams) {
  return createAxios({
    url: '/admin/updatePassword',
    method: 'put',
    data: data,
  })
}

/** 修改管理员头像 */
export async function updateAvatar() {
  return createAxios({
    url: '/admin/avatar',
    method: 'post',
  })
}

/** 获取管理员登录日志 */
export async function loginRecord() {
  return createAxios<ISysLoginRecord[]>({
    url: '/admin/login/record',
  })
}