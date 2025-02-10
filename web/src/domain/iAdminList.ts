/**
 * 管理员列表
 */
export interface IAdminUserList {
  user_id?: number;
  username?: string;
  nickname?: string;
  avatar?: string;
  avatar_url?: string;
  email?: string;
  mobile?: string;
  status?: number;
  group_id?: number;
  sex?: number;
  role_id?: number;
  role_name?: string;
  dept_id?: number;
  dept_name?: string;
  rules?: string[];
  create_time?: string;
  update_time?: string;
}
