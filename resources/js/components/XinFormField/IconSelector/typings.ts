/**
 * 图标选择器组件属性
 */
export interface IconSelectProps {
  /**
   * 选中的图标名称
   */
  value?: string;

  /**
   * 值变化回调
   * @param value 选中的图标名称或 null（清空时）
   */
  onChange?: (value: string | null) => void;

  /**
   * 占位符文本
   * @default '请选择图标'
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
}
