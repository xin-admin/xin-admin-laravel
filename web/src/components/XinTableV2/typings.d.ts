import {
  ActionType,
  FormProps,
  ProColumns,
  ProFormColumnsType,
  ProFormInstance,
  ProTableProps,
} from '@ant-design/pro-components';
import React, { ReactNode } from 'react';
import { ColProps, ModalProps, RowProps } from 'antd';

export type XinTableColumnType<T> = ProFormColumnsType<T> & ProColumns<T>;

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
  // 标题
  title?: ReactNode;
  // Antd Form Props
  formProps?: {
    layout?: FormProps['layout']
    labelAlign?: FormProps['labelAlign']
    labelWrap?: FormProps['labelWrap']
    labelCol?: FormProps['labelCol']
    wrapperCol?: FormProps['wrapperCol']
  };
  // Antd Model Props
  modelProps?: ModalProps
  // Grid 布局
  grid?: boolean
  rowProps?: RowProps
  colProps?: ColProps
}

export type TableRenderProps<T> = {
  // API
  api: string;
  // 标题
  title: ReactNode;
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
  // 工具栏渲染
  toolBarRender?: React.ReactNode[];
  // 操作栏渲染
  operateRender?: React.ReactNode;
  // 表格操作列显示
  operateShow?: boolean;
  // 新增按钮显示
  addShow?: boolean;
  // 编辑按钮显示
  editShow?: boolean;
  // 删除按钮显示
  deleteShow?: boolean;
  // ProTableProps
  tableProps?: ProTableProps<T, any>;
}

