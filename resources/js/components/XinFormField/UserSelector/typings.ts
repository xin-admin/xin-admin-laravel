import type ISysUser from '@/domain/iSysUser';

/**
 * 用户选择器的值类型
 * 支持单个ID、ID数组、或完整的用户对象
 */
export type UserSelectorValue = 
  | number 
  | number[] 
  | ISysUser 
  | ISysUser[] 
  | null 
  | undefined;

/**
 * 用户选择器组件属性
 */
export interface UserSelectorProps {
  /**
   * 选中的值
   * - 单选模式：number | ISysUser | null
   * - 多选模式：number[] | ISysUser[]
   */
  value?: UserSelectorValue;

  /**
   * 值变化回调
   * - 单选模式：返回 number | null
   * - 多选模式：返回 number[]
   */
  onChange?: (value: number | number[] | null) => void;

  /**
   * 选择模式
   * @default 'single'
   */
  mode?: 'single' | 'multiple';

  /**
   * 占位符文本
   */
  placeholder?: string;

  /**
   * 是否禁用
   * @default false
   */
  disabled?: boolean;

  /**
   * 是否只读（只读模式下不能打开选择弹窗）
   * @default false
   */
  readonly?: boolean;

  /**
   * 是否显示部门信息
   * @default true
   */
  showDept?: boolean;

  /**
   * 多选模式下最多显示的标签数量
   * 超出部分会显示 +N
   * @default 2
   */
  maxTagCount?: number;
}
