export interface IDictItem {
  /** ID */
  id?: number;
  /** 字典ID */
  dict_id?: number;
  /** 标签 */
  label?: string;
  /** 值 */
  value?: string;
  /** 是否启用：0：禁用，1：启用 */
  switch?: number;
  /** 状态 （default,success,error,processing,warning） */
  status?: number;
  /** 创建时间 */
  created_at?: string;
  /** 更新时间 */
  updated_at?: string;
}
