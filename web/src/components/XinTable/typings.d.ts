import {
  ActionType,
  BaseQueryFilterProps,
  ProColumns, ProFormColumnsType, ProTableProps,
} from '@ant-design/pro-components';
import {react} from "@babel/types";

type ProFormColumnsAndProColumns<T> = ProFormColumnsType<T> &
  ProColumns<T>;

export type TableProps<TableData> = {
  /**
   * Api 配置
   */
  tableApi: string;
  /**
   * 表头配置
   */
  columns: ProFormColumnsAndProColumns<TableData>[];
  /**
   * 是否显示新建
   */
  addShow?: boolean;
  /**
   * 是否显示搜索
   */
  rowSelectionShow?: boolean;
  /**
   * 是否显示操作栏
   */
  operateShow?: boolean;
  /**
   * 是否显示编辑按钮
   */
  editShow?: boolean;
  /**
   * 是否显示删除按钮
   */
  deleteShow?: boolean;
  optionsShow?: boolean;
  /**
   * 自定义操作栏
   * @param record
   */
  operateRender?: (record: TableData) => JSX.Element | JSX.Element[];
  /**
   * 查询表单配置 参考 ProTable 配置
   */
  searchConfig?: BaseQueryFilterProps & {
    filterType?: 'query' | 'light';
  };
  /**
   * 自定义更新提交事件
   */
  handleUpdate?: (formData:TableData) => Promise<boolean>
  /**
   * 自定义新建提交事件
   */
  handleAdd?: (formData:TableData) => Promise<boolean>
  /**
   * 添加成功事件，重写 handleAdd 此功能失效
   */
  addBefore?: () => void
  /**
   * access 权限前缀
   */
  accessName?: string
  optionProps?: ProFormColumnsAndProColumns<TableData>
  /**
   * 底部按钮
   */
  footerBarButton?: React.ReactNode
  actionRef?:  React.MutableRefObject<ActionType | undefined>
} & ProTableProps<TableData, any>;

/**
 * 创建表单参数
 */
export interface CreateFormProps<T> {
  columns: ProFormColumnsAndProColumns<T>[];
  api: string;
  /**
   * 表格实例
   */
  tableRef: react.Ref<any>;
  /**
   * 自定义新建提交事件
   */
  handleAdd?: (formData:T) => Promise<boolean>
  /**
   * 添加成功事件，重写 handleAdd 此功能失效
   */
  addBefore?: () => void
}

/**
 * 编辑表单参数
 */
export interface UpdateFromProps<T> {
  /**
   * 编辑表头
   */
  columns: ProFormColumnsAndProColumns<T>[];  // 表头
  /**
   * 编辑数据
   */
  values: Partial<T>; // 数据
  /**
   * 编辑行ID
   */
  id: number;
  /**
   * 编辑接口
   */
  api: string;
  /**
   * 表格实例
   */
  tableRef: react.Ref<any>;
  /**
   * 自定义编辑事件
   */
  handleUpdate?: (formData:T) => Promise<boolean>
}
