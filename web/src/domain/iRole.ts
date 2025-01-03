/**
 * 角色
 */
export interface IRole {
  role_id?: number;
  name?: string;
  sort?: string;
  rules?: number[];
  create_time?: string;
  update_time?: string;
}
