/**
 * 系统设置
 */
export interface ISetting {
  id?: number;
  key?: string;
  title?: string;
  describe?: string;
  type?: string;
  values?: string;
  options?: any;
  props?: any;
  group_id?: number;
  sort?: number;
  created_at?: string;
  updated_at?: string;
}
