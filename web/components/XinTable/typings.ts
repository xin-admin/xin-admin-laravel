import type {
  TableProps,
  TableColumnType,
  CardProps,
  FormInstance,
  PaginationProps,
} from 'antd';
import type {ReactNode, RefObject, Dispatch, SetStateAction} from 'react';
import type { FormColumn } from '@/components/XinFormField/FieldRender/typings';
import type { SearchFormProps } from './SearchForm';
import type {XinFormProps, XinFormRef} from '@/components/XinForm/typings';

/**
 * 表单模式
 */
export type FormMode = 'create' | 'update';

/**
 * 表格列配置
 */
export type XinTableColumn<T = any> = Omit<TableColumnType<T>, 'dataIndex'> & {
  hideInSearch?: boolean;
  hideInForm?: boolean;
  hideInTable?: boolean;
  hideInUpdate?: boolean;
  hideInCreate?: boolean;
  search?: FormColumn<T>;
} & FormColumn<T>;

/**
 * XinTable 实例
 */
export interface XinTableInstance<T = any> {
  /** 刷新表格（保持当前页） */
  reload: (resetPage?: boolean) => Promise<void>;
  /** 重置表格到第一页并刷新 */
  reset: () => Promise<void>;
  /** 获取当前数据源 */
  getDataSource: () => T[];
  /** 设置数据源 */
  setDataSource: Dispatch<SetStateAction<T[]>>;
  /** 获取数据总数 */
  getTotal: () => number;
  /** 获取加载状态 */
  getLoading: () => boolean;
  /** 设置加载状态 */
  setLoading: Dispatch<SetStateAction<boolean>>;
  /** 设置分页参数 */
  setPageInfo: (page?: number, pageSize?: number) => void;
  /** 获取表单实例 */
  getForm: () => XinFormRef<T> | null | undefined;
  /** 获取搜索表单实例 */
  getSearchForm: () => FormInstance<T> | undefined;
}

export interface SorterParams {
  field: string;
  order: 'asc' | 'desc';
}

/** 请求参数类型 */
export interface RequestParams extends Record<string, any> {
  page?: number;
  pageSize?: number;
  filter?: Record<string, any>;
  sorter?: SorterParams;
  keywordSearch?: string;
}

/**
 * XinTable 组件属性
 */
export interface XinTableProps<T = any> extends Omit<TableProps<T>, 'columns' | 'rowKey' | 'onChange' | 'pagination'> {
  /** API 地址 */
  api: string;
  /** 权限名称前缀 */
  accessName: string;
  /** 主键 */
  rowKey: string;
  /** 列配置 */
  columns: XinTableColumn<T>[];

  /** 表格 ref */
  tableRef?: RefObject<XinTableInstance<T> | null>;

  /** 新增按钮显示 */
  addShow?: boolean;
  /** 编辑按钮显示 */
  editShow?: boolean | ((record: T) => boolean);
  /** 删除按钮显示 */
  deleteShow?: boolean | ((record: T) => boolean);
  /** 搜索栏显示 */
  searchShow?: boolean;
  /** 表格操作列显示 */
  operateShow?: boolean;
  /** 分页显示 */
  paginationShow?: boolean;
  /** 快速搜索显示 */
  keywordSearchShow?: boolean;

  /** 表单属性  */
  formProps?: Omit<XinFormProps<T>, 'onFinish' | 'modalProps' | 'columns' | 'formRef' | 'layoutType'> | false;
  /** 表单属性  */
  modalProps?: XinFormProps<T>['modalProps'];
  /** 搜索栏属性  */
  searchProps?: Omit<SearchFormProps<T>, 'form'> | false;
  /** 操作栏属性 */
  operateProps?: TableColumnType<T>;
  /** 卡片属性 */
  cardProps?: Pick<CardProps, 'variant' | 'hoverable' | 'size' | 'classNames' | 'styles'>;
  /** 分页配置 */
  pagination?: Omit<PaginationProps, 'total' | 'onChange' | 'current'>;

  /** 标题渲染 */
  titleRender?: ReactNode;
  /** 工具栏渲染 */
  toolBarRender?: ReactNode[];
  /** 操作栏之后渲染 */
  beforeOperateRender?: (record: T) => ReactNode;
  /** 操作栏之后渲染 */
  afterOperateRender?: (record: T) => ReactNode;

  /** 自定义请求 */
  handleRequest?: (params: RequestParams) => Promise<{ data: T[]; total: number }>;
  /** 请求参数处理 */
  requestParams?: (params: RequestParams) => RequestParams;
  /** 自定义表单请求 */
  handleFinish?: (values: T, mode: FormMode, formRef: RefObject<XinFormRef<T> | null>, defaultValue?: T) => Promise<boolean>;

  /** 开启批量操作 */
  batchOperation?: boolean;
  /** */
  selectionProps?: TableProps['rowSelection'];
}
