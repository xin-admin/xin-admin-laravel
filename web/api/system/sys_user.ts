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

export interface RoleFieldType {
  /** 角色ID */
  role_id: number;
  /** 角色名称 */
  name: string;
}

export interface DeptFieldType {
  /** 部门ID */
  dept_id: number;
  /** 部门名称 */
  name: string;
  /** 下级部门 */
  children: DeptFieldType[];
}

export interface ResetPasswordType {
  /** 用户ID */
  id: number;
  /** 密码 */
  password: string;
  /** 验证密码 */
  rePassword: string;
}

/** 获取管理员角色选项栏数据 */
export async function roleField() {
  return createAxios<RoleFieldType[]>({
    url: '/system/user/role',
    method: 'get',
  });
}

/** 获取管理员角色选项栏数据 */
export async function deptField() {
  return createAxios<DeptFieldType[]>({
    url: '/system/user/dept',
    method: 'get',
  });
}

/** 获取管理员角色选项栏数据 */
export async function resetPassword(data: ResetPasswordType) {
  return createAxios({
    url: '/system/user/resetPassword',
    method: 'put',
    data
  });
}


/** 后台用户登录 */
export async function login(data: LoginParams) {
  return createAxios<LoginResponse>({
    url: '/system/user/login',
    method: 'post',
    data,
  });
}

/** 后台用户退出登录 */
export async function logout() {
  return createAxios({
    url: '/system/user/logout',
    method: 'post',
  });
}

/** 获取管理员用户信息 */
export async function info() {
  return createAxios<InfoResponse>({
    url: '/system/user/info',
    method: 'get',
  });
}

/** 获取管理员用户信息 */
export async function menu() {
  return createAxios<MenuResponse>({
    url: '/system/user/menu',
    method: 'get',
  });
}

/** 更改管理员信息 */
export async function updateInfo(info: InfoParams) {
  return createAxios({
    url: '/system/user/updateInfo',
    method: 'put',
    data: info,
  })
}

/** 修改管理员密码 */
export async function updatePassword(data: PasswordParams) {
  return createAxios({
    url: '/system/user/updatePassword',
    method: 'put',
    data: data,
  })
}

/** 修改管理员头像 */
export async function updateAvatar() {
  return createAxios({
    url: '/system/user/uploadAvatar',
    method: 'post',
  })
}

/** 获取管理员登录日志 */
export async function loginRecord() {
  return createAxios<ISysLoginRecord[]>({
    url: '/system/user/loginRecord',
  })
}
