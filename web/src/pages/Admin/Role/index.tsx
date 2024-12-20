import XinTable from '@/components/XinTable';
import { ProFormColumnsAndProColumns, TableProps } from '@/components/XinTable/typings';
import React, { useRef, useState } from 'react';
import { Card, Col, message, Popconfirm, Row, Tree } from 'antd';
import {deleteApi} from '@/services/common/table';
import { ActionType } from '@ant-design/pro-components';
import ButtonAccess from '@/components/ButtonAccess';

export interface GroupListType {
  role_id?: number;
  name?: string;
  sort?: string;
  rules?: number[];
  create_time?: string;
  update_time?: string;
}

export default () => {
  const [selectedGroup, setSelectedGroup] = useState<GroupListType>();

  const actionRef = useRef<ActionType>()

  const columns: ProFormColumnsAndProColumns<GroupListType>[] = [
    {
      title: 'ID',
      dataIndex: 'role_id',
      hideInForm: true,
      hideInTable: true
    },
    {
      title: '角色名',
      dataIndex: 'name',
      colProps: { span: 24 },
    },
    {
      title: '排序',
      dataIndex: 'sort',
      valueType: 'digit',
      colProps: { span: 24 },
    },
    {
      title: '描述',
      dataIndex: 'description',
      valueType: 'textarea',
      colProps: { span: 24 },
    },
    {
      title: '创建时间',
      dataIndex: 'created_at',
      valueType: 'date',
      hideInForm: true
    },
    {
      title: '编辑时间',
      dataIndex: 'updated_at',
      valueType: 'date',
      hideInForm: true,
    },
  ];

  // 删除节点
  const handleRemove = (selectedRows: GroupListType[]) => {
    const hide = message.loading('正在删除');
    let ids = selectedRows.map(x => x.role_id)
    deleteApi('/admin/role', { ids: ids.join() || '' }).then( res => {
      if (res.success) {
        message.success(res.msg).then();
        actionRef.current?.reloadAndRest?.();
      }else {
        message.warning(res.msg).then();
      }
    }).finally(() => hide())
  }

  // 操作栏
  const operate = (data: GroupListType) => {
    if (data.role_id === 1 ) return <></>
    return (
      <>
        <ButtonAccess auth={'admin.role.delete'}>
          <Popconfirm
            title="Delete the task"
            description="你确定要删除这条数据吗？"
            onConfirm={() => { handleRemove([data]) }}
            okText="确认"
            cancelText="取消"
          >
            <a>删除</a>
          </Popconfirm>
        </ButtonAccess>
      </>
    )
  }

  // 表格属性
  const tableProps: TableProps<GroupListType> = {
    headerTitle: '分组列表',
    tableApi: '/admin/role',
    accessName: 'admin.role',
    search: false,
    columns,
    actionRef,
    operateRender: operate,
    pagination: false,
    deleteShow: false,
  }

  return (
    <Row gutter={[20, 20]}>
      <Col span={16}>
        <XinTable<GroupListType> {...tableProps} />
      </Col>
      <Col span={8}>
        <Card>
          {
            selectedGroup && <Tree
              checkable
              defaultExpandAll
              blockNode={false}
              fieldNames={{
                title: 'name',
                key: 'id',
                children: 'children',
              }}
              showLine
            />
          }
        </Card>
      </Col>
    </Row>
  )
}
