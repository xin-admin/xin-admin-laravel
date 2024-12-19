import { listApi, deleteApi } from '@/services/common/table';
import {
  ActionType,
  FooterToolbar,
  ProTable
} from '@ant-design/pro-components';
import { Button, message, Divider, Watermark, Popconfirm, Space } from 'antd';
import React, { ReactNode, useImperativeHandle, useRef, useState } from 'react';
import { ProFormColumnsAndProColumns, TableProps } from './typings';
import UpdateForm from './components/UpdateForm';
import CreateForm from './components/CreateForm';
import { ProTableProps } from "@ant-design/pro-components";
import ButtonAccess from '@/components/ButtonAccess';


function XinTable<TableData extends Record<string, any>>(props: TableProps<TableData>) {
  const {
    tableApi,
    columns,
    addShow,
    deleteShow,
    editShow ,
    searchConfig,
    rowSelectionShow,
    operateRender,
    operateShow,
    handleUpdate,
    handleAdd,
    addBefore,
    accessName,
    footerBarButton,
    optionProps
  } = props;

  /**
   * 表单全部数据
   */
  const [dataSource, setDataSource] = useState<TableData[]>([]);
  /**
   * 表格实例  REF
   */
  const actionRef = useRef<ActionType>();
  /**
   * 多选状态
   */
  const [selectedRowsState, setSelectedRows] = useState<TableData[]>([]);
  /**
   *  节点展开所有
   */
  const [expandedRowKeys, setExpandedRowKeys] = useState<React.Key[]>([]);
  /**
   * 表格所有节点的Key
   */
  const [allKeys, setAllKeys] = useState([]);

  useImperativeHandle(props.actionRef,()=> actionRef.current)

  /**
   * 递归收集所有 Key
   * @param data
   */
  const collectKeys = (data: TableData[]) => {
    let keys: any = [];
    data.forEach((item) => {
      keys.push(item.id);
      if (item.children) {
        keys = keys.concat(collectKeys(item.children));
      }
    });
    return keys;
  };

  /**
   *  删除节点
   * @param selectedRows
   */
  const handleRemove = async (selectedRows: TableData[]) => {
    const hide = message.loading('正在删除');
    if (!selectedRows || deleteShow === false){
      message.warning('请选择需要删除的节点');
      return
    }
    let ids = selectedRows.map(x => x.id)
    deleteApi(tableApi, { ids: ids.join() || '' }).then( res => {
      if (res.success) {
        message.success(res.msg);
        actionRef.current?.reloadAndRest?.();
      }else {
        message.warning(res.msg);
      }
    }).finally(() => hide())
  }

  /**
   * 删除按钮
   * @param record
   */
  const deleteButton = (record: TableData) => {
    return (
      <ButtonAccess auth={ accessName + '.delete' }>
        <Popconfirm
          title="Delete the task"
          description="你确定要删除这条数据吗？"
          onConfirm={() => { handleRemove([record]) }}
          okText="确认"
          cancelText="取消"
        >
          <a>删除</a>
        </Popconfirm>
      </ButtonAccess>
    )
  }

  /**
   * 编辑按钮
   * @param record
   */
  const editButton = (record: TableData) => {
    return (
      <ButtonAccess auth={ accessName + '.edit' }>
        <UpdateForm<TableData>
          values={record}
          columns={columns}
          id={record.id}
          api={tableApi}
          tableRef={actionRef}
          handleUpdate={handleUpdate}
        />
      </ButtonAccess>
    )
  }

  /**
   * 新增按钮
   */
  const addButton = () => {
    return (
      <ButtonAccess auth={ accessName + '.add' }>
        <CreateForm<TableData>
          columns = { columns }
          api={tableApi}
          tableRef={actionRef}
          handleAdd={handleAdd}
          addBefore={addBefore}
        />
      </ButtonAccess>
    )
  }

  /**
   * 默认操作栏按钮
   */
  const defaultButton = () => {
    let operate: ProFormColumnsAndProColumns<TableData> =  {
      title: '操作',
      dataIndex: 'option',
      valueType: 'option',
      render: (_, record) => (
        <Space split={<Divider type="vertical" />} size={0}>
          {editShow !== false && editButton(record)}
          {deleteShow !== false && deleteButton(record)}
          {operateRender !== undefined && operateRender(record)}
        </Space>
      ),
      ...optionProps
    }
    return operate
  }

  /**
   * 工具栏默认渲染
   */
  const defaultToolBar: ProTableProps<TableData, any>['toolBarRender'] = (action,rows) => {
    let bar: ReactNode[] = [addShow !== false ? addButton() : '',];
    if(allKeys.length && allKeys.length > dataSource.length) {
      bar.push((
        <>
          <Button onClick={() => setExpandedRowKeys(allKeys)}>
            展开全部
          </Button>
          <Button onClick={() => setExpandedRowKeys([])}>
            折叠全部
          </Button>
        </>
      ))
    }
    if(props.toolBarRender) {
      bar.push(props.toolBarRender(action,rows))
    }
    return bar
  }

  /**
   * 多选底部操作栏
   */
  const footerBar = () => {
    return selectedRowsState?.length > 0 && (
      <FooterToolbar
        extra={
          <div>
            已选择{' '}
            <a style={{ fontWeight: 600 }}>{selectedRowsState.length}</a>{' '}
            项&nbsp;&nbsp;
          </div>
        }
      >
        <ButtonAccess auth={ accessName + '.delete' }>
          <Button
            danger
            onClick={async () => {
              await handleRemove(selectedRowsState);
              setSelectedRows([]);
              actionRef.current?.reloadAndRest?.();
            }}
          >
            批量删除
          </Button>
          {footerBarButton}
        </ButtonAccess>
      </FooterToolbar>
    )
  }

  /**
   * 表格默认属性
   */
  const defaultProTableConfig: ProTableProps<TableData, any> = {
    headerTitle: "查询表格",
    rowKey: "id",
    search: searchConfig,
    expandable: {
      expandedRowKeys: expandedRowKeys,
      onExpandedRowsChange: (expandedKeys) => {
        setExpandedRowKeys([...expandedKeys])
      }
    },
    request: async (params, sorter, filter) => {
      const { data, success } = await listApi(tableApi, {
        ...params,
        sorter,
        filter,
      });
      setDataSource(data.data);
      setAllKeys(collectKeys(data.data));
      return {
        ...data,
        success,
      };
    },
    rowSelection: rowSelectionShow !== false ? { onChange: (_, selectedRows) => setSelectedRows(selectedRows) } : undefined,
    tableStyle: {minHeight: 500},
    pagination: { showSizeChanger: true }
  }

  return (
    <Watermark>
      <ProTable<TableData>
        { ...Object.assign(defaultProTableConfig, props) }
        columns={operateShow!==false?[...columns,defaultButton()]:columns}
        toolBarRender={defaultToolBar}
        cardBordered
        actionRef={actionRef}
      />
      {footerBar()}
    </Watermark>
  );
}

export default XinTable;
