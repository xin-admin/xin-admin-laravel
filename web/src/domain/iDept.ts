/**
 * 管理员部门表
 */
export interface IDept {
  dept_id?: number;
  parent_id?: number;
  name?: string;
  sort?: number;
  email?: string;
  phone?: string;
  leader?: string;
  status?: number;
  created_at?: string;
  updated_at?: string;
  children?: IDept[];
}
