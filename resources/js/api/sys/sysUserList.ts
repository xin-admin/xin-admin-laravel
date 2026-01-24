import createAxios from '@/utils/request';

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
    url: '/sys-user/list/role',
    method: 'get',
  });
}

/** 获取管理员角色选项栏数据 */
export async function deptField() {
  return createAxios<DeptFieldType[]>({
    url: '/sys-user/list/dept',
    method: 'get',
  });
}

/** 获取管理员角色选项栏数据 */
export async function resetPassword(data: ResetPasswordType) {
  return createAxios({
    url: '/sys-user/list/reset/password',
    method: 'put',
    data
  });
}


