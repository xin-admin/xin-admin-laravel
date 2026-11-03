export default interface ISysUser {
  /** 用户ID */
  id?: number;
  /** 用户名 */
  username?: string;
  /** 头像ID */
  avatar_id?: number;
  /** 创建时间 */
  created_at?: string;
  /** 部门ID */
  dept_id?: number;
  /** 邮箱 */
  email?: string;
  /** 邮箱验证时间 */
  email_verified_at?: string;
  /** 最后登录IP */
  login_ip?: string;
  /** 最后登录时间 */
  login_time?: string;
  /** 手机号 */
  mobile?: string;
  /** 昵称 */
  nickname?: string;
  /** 性别 */
  sex?: number;
  /** 个人简介 */
  bio: string;
  /** 用户状态 */
  status?: number;
  /** 创建时间 */
  updated_at?: string;
  // 下面是附加数据
  /** 头像Url */
  avatar_url?: string;
  /** 角色 */
  roles_field?: {
    /** 角色ID */
    role_id: number;
    /** 角色名称 */
    name: string;
  }[];
  /** 部门名称 */
  dept_name?: string;
}


