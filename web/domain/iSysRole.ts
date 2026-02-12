export interface ISysRole {
  /** 角色ID */
  id?: number;
  /** 角色名称 */
  name?: string;
  /** 排序 */
  sort?: number;
  /** 拥有的权限ID */
  ruleIds?: number[];
  /** 角色描述 */
  description?: string;
  /** 角色用户数量 */
  countUser?: number;
  /** 启用状态 */
  status?: number;
  /** 创建时间 */
  created_at?: string;
  /** 更新时间 */
  updated_at?: string;
}