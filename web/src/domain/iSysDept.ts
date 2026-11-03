/** 部门类型 */
export interface ISysDept {
  /** 部门ID */
  id?: number;
  /** 部门编码 */
  code?: string;
  /** 部门名称 */
  name?: string;
  /** 部门负责人 */
  leader?: string;
  /** 部门类型 0：公司 1：部门 2：岗位 */
  type?: number;
  /** 父级ID */
  parent_id?: number;
  /** 部门邮箱 */
  email?: string;
  /** 部门地址 */
  address?: string;
  /** 部门电话 */
  phone?: string;
  /** 部门备注 */
  remark?: string;
  /** 部门排序 */
  sort?: number;
  /** 部门状态 0：正常 1：停用 */
  status?: number;
  /** 子部门 */
  children?: ISysDept[];
  /** 创建时间 */
  created_at?: string;
  /** 更新时间 */
  updated_at?: string;
}