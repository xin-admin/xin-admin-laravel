import type {
  FormItemProps,
  ColProps,
  FormInstance,
  InputProps,
  InputNumberProps,
  SelectProps,
  TreeSelectProps,
  CascaderProps,
  RadioGroupProps,
  SwitchProps,
  RateProps,
  SliderSingleProps,
  DatePickerProps,
  TimePickerProps,
  TimeRangePickerProps,
  ColorPickerProps
} from 'antd';
import type { TextAreaProps, PasswordProps } from 'antd/es/input';
import type { RangePickerProps } from 'antd/es/date-picker';
import type { CheckboxGroupProps } from 'antd/es/checkbox';
import {type Key, type ReactNode} from 'react';
import type { IconSelectProps } from '@/components/XinFormField/IconSelector/typings';
import type { ImageUploaderProps } from '@/components/XinFormField/ImageUploader/typings';
import type { UserSelectorProps } from '@/components/XinFormField/UserSelector/typings';

/**
 * 表单字段类型
 */
export type FieldValue =
  | 'text'          // 文本输入
  | 'password'      // 密码输入
  | 'textarea'      // 多行文本
  | 'digit'         // 数字输入
  | 'money'         // 金额输入
  | 'select'        // 下拉选择
  | 'treeSelect'    // 树形选择
  | 'cascader'      // 级联选择
  | 'radio'         // 单选框
  | 'radioButton'   // 单选按钮
  | 'checkbox'      // 多选框
  | 'switch'        // 开关
  | 'rate'          // 评分
  | 'slider'        // 滑动条
  | 'date'          // 日期选择
  | 'dateTime'      // 日期时间选择
  | 'dateRange'     // 日期范围
  | 'time'          // 时间选择
  | 'timeRange'     // 时间范围
  | 'week'          // 周选择
  | 'month'         // 月选择
  | 'quarter'       // 季度选择
  | 'year'          // 年选择
  | 'image'         // 图片上传
  | 'color'         // 颜色选择
  | 'icon'          // 图标选择
  | 'user'          // 用户选择

/**
 * valueType 到组件 Props 的类型映射
 */
export interface FieldPropsMap {
  text: InputProps;
  password: PasswordProps;
  textarea: TextAreaProps;
  digit: InputNumberProps;
  money: InputNumberProps;
  select: SelectProps;
  treeSelect: TreeSelectProps;
  cascader: CascaderProps;
  radio: Omit<RadioGroupProps, 'optionType'>;
  radioButton: Omit<RadioGroupProps, 'optionType'>;
  checkbox: CheckboxGroupProps;
  switch: SwitchProps;
  rate: RateProps;
  slider: SliderSingleProps;
  date: DatePickerProps;
  dateTime: DatePickerProps;
  dateRange: RangePickerProps;
  time: TimePickerProps;
  timeRange: TimeRangePickerProps;
  week: DatePickerProps;
  month: DatePickerProps;
  quarter: DatePickerProps;
  year: DatePickerProps;
  image: ImageUploaderProps;
  color: ColorPickerProps;
  icon: IconSelectProps;
  user: UserSelectorProps;
}

/**
 * 字段依赖配置
 */
export interface FieldDependency<T = any> {
  /** 依赖的字段名 */
  dependencies: (keyof T)[];
  /** 根据依赖值判断是否显示 */
  visible?: (values: Partial<T>) => boolean;
  /** 根据依赖值判断是否禁用 */
  disabled?: (values: Partial<T>) => boolean;
  /** 根据依赖值动态修改 fieldProps */
  fieldProps?: (values: Partial<T>) => Record<string, any>;
}

/**
 * 根据 valueType 映射 fieldProps 类型的表单列配置
 */
type FormColumnMap<T> = {
  [K in FieldValue]: FormItemProps<T> & {
  /** 唯一标识 */
  key?: Key;
  /** 字段名 */
  dataIndex?: string;
  /** 字段标签 */
  title?: string;
  /** 字段类型 */
  valueType?: K;
  /** 自定义字段渲染 */
  fieldRender?: (form: FormInstance<T>) => ReactNode;
  /** Col 属性 表单开启 grid 时生效 */
  colProps?: ColProps;
  /** 字段组件的属性，根据 valueType 自动推断类型 */
  fieldProps?: FieldPropsMap[K];
  /** 字段依赖配置 */
  dependency?: FieldDependency<T>;
}
}

/**
 * 表单列配置
 * fieldProps 类型会根据 valueType 自动推断
 */
export type FormColumn<T> = FormColumnMap<T>[FieldValue]
