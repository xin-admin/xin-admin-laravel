export interface ISetting {
  /** ID */
  id?: number;
  /** 键 */
  key?: string;
  /** 标题 */
  title?: string;
  /** 描述 */
  describe?: string;
  /** 值 */
  values?: string;
  /** 类型 */
  type?: string;
  /** 选项 */
  options?: string;
  /** 选项JSON */
  options_json?: string;
  /** 属性 */
  props?: string;
  /** 属性JSON */
  props_json?: string;
  /** 组ID */
  group_id?: number;
  /** 排序 */
  sort?: number;
  /** 创建时间 */
  created_at?: string;
  /** 更新时间 */
  updated_at?: string;
}
