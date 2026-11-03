import type { ButtonProps, ColProps, FormInstance, FormProps, RowProps } from "antd";
import type { ReactNode } from "react";
import type { FormColumn } from "@/components/XinFormField/FieldRender";

/**
 * 操作栏按钮
 */
export type SubmitterButton = {
  /** 查询按钮 */
  search: ReactNode;
  /** 重置按钮 */
  reset: ReactNode;
  /** 折叠按钮 */
  collapse?: ReactNode;
}

/**
 * 表单操作栏属性
 */
export interface SubmitterProps {
  /** 操作栏渲染 */
  render?: false | ((dom: SubmitterButton) => ReactNode);
  /** 提交按钮文本 */
  submitText?: string | ReactNode;
  /** 重置按钮文本 */
  resetText?: string | ReactNode;
  /** 提交按钮属性 */
  submitButtonProps?: Omit<ButtonProps, 'loading' | 'onClick'>;
  /** 重置按钮属性 */
  resetButtonProps?: Omit<ButtonProps, 'loading' | 'onClick'>;
  /** 折叠按钮渲染 */
  collapseRender?: (collapse: boolean) => ReactNode;
}

/**
 * XinSearch 组件属性
 */
export type SearchFormProps<T = any> = Omit<FormProps<T>, 'onFinish' | 'form'> & {
  /** 表单列配置 */
  columns: FormColumn<T>[];
  /** 表单实例引用 */
  form?: FormInstance<T>;
  /** 表单提交 */
  handleSearch?: (values: T) => Promise<boolean | void>;
  /** 渲染表单操作栏 */
  submitter?: SubmitterProps;
  /** 是否使用 Grid 布局 */
  grid?: boolean;
  /** 开启 grid 模式时传递给 Row */
  rowProps?: RowProps;
  /** 传递给表单项的 Col */
  colProps?: ColProps;
}
