export interface ISettingGroup {
  /** ID */
  id?: number;
  /** 标题 */
  title?: string;
  /** 键 */
  key?: string;
  /** 描述 */
  remark?: string;
  /** 创建时间 */
  created_at?: Date;
  /** 更新时间 */
  updated_at?: Date;
}