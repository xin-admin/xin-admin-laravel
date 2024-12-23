import {
  ActionType,
  FormProps,
  ProColumns,
  ProFormColumnsType,
  ProFormInstance,
  ProTableProps,
} from '@ant-design/pro-components';
import React, { ReactNode } from 'react';
import { ColProps, ModalProps, RowProps, TableProps } from 'antd';

export type XinTableColumnType<T> = ProFormColumnsType<T> & ProColumns<T>;

// CRUD 表格渲染 Props
export type XinTablePropsType<T> = {
  // 表单 columns
  columns: XinTableColumnType<T>[];
  // API
  api: string;
  // 主键
  rowKey: string;
  // 权限
  accessName: string;
  // 表单扩展配置
  formProps?: FormPropsType;
  // 表格扩展配置
  tableProps?: TablePropsType<T>;
}

// 扩展表单配置项 Props
export interface FormPropsType {
  // 表单 layout
  layout?: FormProps['layout']
  // 表单项 label Align
  labelAlign?: FormProps['labelAlign']
  // 表单项 Label Warp
  labelWrap?: FormProps['labelWrap']
  // 表单项 Label Col
  labelCol?: FormProps['labelCol']
  // 表单项 Wrapper Col
  wrapperCol?: FormProps['wrapperCol']
  // Grid 布局
  grid?: boolean
  // Row Props
  rowProps?: RowProps
  // Col Props
  colProps?: ColProps
}

// 表格扩展配置 Props
export type TablePropsType<T> =  ProTableProps<T, any> & {
  // 工具栏渲染
  toolBar?: React.ReactNode[];
  // 操作栏渲染
  operate?: (record: T) => React.ReactNode;
  // 表格操作列显示
  operateShow?: boolean;
  // 新增按钮显示
  addShow?: boolean;
  // 编辑按钮显示
  editShow?: boolean;
  // 删除按钮显示
  deleteShow?: boolean;
}

// 表单渲染 Props
export type FormRenderProps<FormData> = {
  // 表单 columns
  columns: ProFormColumnsType[];
  // 显示状态
  open: boolean;
  // API
  api: string;
  // 主键
  rowKey: string;
  // 表单 Ref
  formRef: React.MutableRefObject<ProFormInstance | undefined>;
  // 表单提交完成后
  afterFinish?: (formData: FormData, initialValue?: FormData | false) => void;
  // 初始数据
  initialValue: FormData | false;
  // Antd Model Props
  modelProps?: ModalProps;
  // Antd Form Props
  formProps?: FormPropsType;
}

// 表格渲染 Props
export type TableRenderProps<T> = {
  // API
  api: string;
  // 主键
  rowKey: string;
  // 表格 columns
  columns: ProColumns<T>[];
  // 权限
  accessName: string;
  // 表格 Ref
  actionRef: React.MutableRefObject<ActionType | undefined>;
  // 打开表单
  openForm: () => void;
  // 设置表单默认值
  setFormInitValue: (record: T | false) => void;
  // 表格扩展配置
  tableProps?: TablePropsType<T>;
}
