// 文件夹相关类型定义
export interface ISysFileGroup {
  /** 文件夹ID */
  id: number;
  /** 文件夹名称 */
  name: string;
  /** 排序 */
  sort?: number;
  /** 父文件夹ID */
  parent_id?: string;
  /** 描述 */
  describe?: string;
  /** 文件总数 */
  countFiles?: number;
  /** 创建时间 */
  created_at?: string;
  /** 更新时间 */
  updated_at?: string;
  /** 子文件夹 */
  children?: ISysFileGroup[];
}
