import ButtonAccess from '@/components/ButtonAccess';
import CreateFormRender from '@/pages/Admin/List/components/CreateFormRender';
import { listApi } from '@/services/common/table';
import { ActionType, ProColumns, type ProFormColumnsType, ProTable } from '@ant-design/pro-components';
import React from 'react';
import { Button, Divider, Space } from 'antd';
import { useBoolean } from 'ahooks';

type TableColumns<T> = ProFormColumnsType<T> & ProColumns<T>;

interface TableRenderProps<TableData> {
  api: string;
  rowKey: string;
  accessName: string;
  openForm: () => void;
  setInitialValue: (value: TableData | undefined) => void;
  columns: TableColumns<TableData>[];
  actionRef: React.MutableRefObject<ActionType | undefined>;
  addRender?: (record: TableData) => React.ReactNode | false;
  editRender?: (record: TableData) => React.ReactNode | false;
  deleteRender?: (record: TableData) => React.ReactNode | false;
  operateRender?: (record: TableData) => React.ReactNode | false;
}

export default function TableRender<TableData extends Record<string, any>>(props: TableRenderProps<TableData>) {

  const {accessName, actionRef, openForm, setInitialValue} = props

  const [openTest, setB] = useBoolean(false);

  // 编辑按钮
  const EditButton: React.FC<{ record: TableData }> = (props) => {
    return (
      <>
        {
          <ButtonAccess auth={ accessName + '.edit' }>
            <Button onClick={() => { setInitialValue(props.record); openForm()}}>编辑</Button>
          </ButtonAccess>
        }
      </>
    )
  }

  const operateColumns: TableColumns<TableData> = {
    title: '操作',
    dataIndex: 'option',
    valueType: 'option',
    render: (_, record) => (
      <Space split={ <Divider type="vertical" /> } size={0}>
        <EditButton record={record}/>
        {deleteShow !== false && deleteButton(record)}
        {operateRender !== undefined && operateRender(record)}
      </Space>
    ),
  }


  return (
    <ProTable<TableData>
      headerTitle={'管理员列表'}
      columns={props.columns}
      actionRef={props.actionRef}
      rowKey={props.rowKey}
      toolBarRender={() => [
        <ButtonAccess auth={'admin.list.add'}>
          <CreateFormRender columns={columns} actionRef={actionRef}/>
        </ButtonAccess>
      ]}
      request={async (params, sorter, filter) => {
        const { data, success } = await listApi('/admin/list', { ...params, sorter, filter });
        return { ...data, success, }
      }}
    />
  )
}
