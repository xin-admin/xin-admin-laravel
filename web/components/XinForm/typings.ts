import type {
  FormProps,
  RowProps,
  ModalProps,
  DrawerProps,
  FormInstance,
  ButtonProps,
  ColProps,
} from 'antd';
import {type ReactNode, type RefObject} from 'react';
import type { FormColumn } from '@/components/XinFormField/FieldRender/typings';

/**
 * 表单操作栏按钮
 */
export type SubmitterButton = {
  /** 提交按钮 */
  submit: ReactNode;
  /** 重置按钮 */
  reset: ReactNode;
  /** 关闭按钮 */
  close: ReactNode;
}

/**
 * 表单操作栏属性
 */
export interface SubmitterProps {
  /** 操作栏渲染 */
  render?: false | ((dom: SubmitterButton, form?: RefObject<XinFormRef | undefined>) => ReactNode);
  /** 提交按钮文本 */
  submitText?: string | ReactNode;
  /** 重置按钮文本 */
  resetText?: string | ReactNode;
  /** 关闭按钮文本 */
  closeText?: string | ReactNode;
  /** 提交按钮属性 */
  submitButtonProps?: Omit<ButtonProps, 'loading' | 'onClick'>;
  /** 重置按钮属性 */
  resetButtonProps?: Omit<ButtonProps, 'loading' | 'onClick'>;
  /** 关闭按钮属性 */
  closeButtonProps?: Omit<ButtonProps, 'loading' | 'onClick'>;
}

/**
 * XinForm 实例方法
 */
export interface XinFormRef<T = any> extends FormInstance<T> {
  /** 打开弹窗/抽屉 (仅 ModalForm/DrawerForm 有效) */
  open: () => void;
  /** 关闭弹窗/抽屉 (仅 ModalForm/DrawerForm 有效) */
  close: () => void;
  /** 获取弹窗/抽屉的打开状态 */
  isOpen: () => boolean;
  /** 设置加载状态 */
  setLoading: (loading: boolean) => void;
}

/**
 * XinForm 组件属性
 */
export type XinFormProps<T = any> = Omit<FormProps<T>, 'onFinish' | 'form'> & {
  /** 表单列配置 */
  columns: FormColumn<T>[];
  /** 表单布局类型 */
  layoutType?: 'Form' | 'ModalForm' | 'DrawerForm';
  /** 是否使用 Grid 布局 */
  grid?: boolean;
  /** 开启 grid 模式时传递给 Row */
  rowProps?: RowProps;
  /** 传递给表单项的 Col */
  colProps?: ColProps;
  /** 表单提交 */
  onFinish?: (values: T) => Promise<boolean | void>;
  /** 表单实例引用 */
  formRef?: RefObject<XinFormRef | undefined>;
  /** ModalForm 弹窗配置 */
  modalProps?: Omit<ModalProps, 'open'>;
  /** DrawerForm 抽屉配置 */
  drawerProps?: Omit<DrawerProps, 'open'>;
  /** 触发器 */
  trigger?: ReactNode;
  /** 渲染表单操作栏 */
  submitter?: SubmitterProps;
}
