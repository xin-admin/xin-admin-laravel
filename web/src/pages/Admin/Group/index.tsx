import XinTable from '@/components/XinTable';
import { ProFormColumnsAndProColumns, TableProps } from '@/components/XinTable/typings';
import React, { useRef, useState } from 'react';
import { useAsyncEffect } from 'ahooks';
import GroupRule from './components/GroupRule';
import * as tableApi from '@/services/common/table';
import { Button, Card, Col, Drawer, message, Popconfirm, Row, Space, Tree } from 'antd';
import {addApi, deleteApi, editApi} from '@/services/common/table';
import { ActionType } from '@ant-design/pro-components';
import ButtonAccess from '@/components/ButtonAccess';

export interface GroupListType {
  id?: number;
  name?: string;
  pid?: number;
  rules?: number[];
  children?: GroupListType[];
  create_time?: string;
  update_time?: string;
}

export default () => {
  const [treeData, setTreeData] = useState<GroupListType[]>([]);
  const [selectedGroup, setSelectedGroup] = useState<GroupListType>();

  useAsyncEffect(async () => {
    let res = await tableApi.listApi('/admin/group')
    setTreeData(res.data.data)
  },[])

  const actionRef = useRef<ActionType>()

  const columns: ProFormColumnsAndProColumns<GroupListType>[] = [
    {
      title: '类型',
      dataIndex: 'type',
      valueType: 'radio',
      hideInTable: true,
      initialValue: '0',
      formItemProps: {
        rules: [
          { required: true, message: '此项为必填项' },
        ],
      },
      valueEnum: {
        '0': '根节点',
        '1': '子节点'
      }
    },
    {
      title: 'ID',
      dataIndex: 'id',
      hideInForm: true,
      hideInTable: true
    },
    {
      title: '分组名',
      dataIndex: 'name',
      valueType: 'text',
    },
    {
      valueType: 'dependency',
      name: ['type'],
      hideInTable: true,
      columns: ({ type }: any): ProFormColumnsAndProColumns<GroupListType>[] => {
        return type !== '0' ? [
          {
            title: '父节点',
            dataIndex: 'pid',
            valueType: 'treeSelect',
            initialValue: 1,
            fieldProps: {
              fieldNames: {
                label: 'name',
                value: 'id',
              },
            },
            request: async () => treeData,
            formItemProps: {
              rules: [
                { required: true, message: '此项为必填项' },
              ],
            },
          }
        ] : []
      }
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
    let ids = selectedRows.map(x => x.id)
    deleteApi('/admin/group', { ids: ids.join() || '' }).then( res => {
      if (res.success) {
        message.success(res.msg).then();
        actionRef.current?.reloadAndRest?.();
      }else {
        message.warning(res.msg).then();
      }
    }).finally(() => hide())
  }

  // 添加节点
  const handleAdd = async (record: any) => {
    let data: any = {}
    if(record.type === '0') {
      data.pid = 0;
      data.name = record.name
    }else {
      data.pid = record.pid;
      data.name = record.name;
    }
    await addApi('/admin/group', data);
    message.success('添加成功！');
    return true;
  }

  // 更新节点
  const handleUpdate = async (record: any) => {
    let data: GroupListType = { id: record.id }
    if(record.id === record.pid) {
      message.warning('不能以自身为父节点！');
      return false;
    }
    if(record.type === '0') {
      data.pid = 0;
      data.name = record.name
    }else {
      data.pid = record.pid;
      data.name = record.name;
    }
    await editApi('/admin/group', data);
    message.success('添加成功！');
    return true;
  }

  // 操作栏
  const operate = (data: GroupListType) => {
    if (data.id === 1 ) return <></>
    return (
      <>
        <ButtonAccess auth={'admin.group.delete'}>
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

  const tableProps: TableProps<GroupListType> = {
    headerTitle: '分组列表',
    tableApi: '/admin/group',
    accessName: 'admin.group',
    columns,
    search: false,
    deleteShow: false,
    actionRef,
    handleAdd,
    handleUpdate,
    operateRender: operate
  }

  return (
    <Row gutter={[20, 20]}>
      <Col span={12}>
        <XinTable<GroupListType> {...tableProps} />
      </Col>
      <Col span={12}>
        <Card>
          {
            selectedGroup && <Tree
              checkable
              defaultExpandAll
              blockNode={false}
              treeData={treeData}
              fieldNames={{
                title: 'name',
                key: 'id',
                children: 'children',
              }}
              showLine
              onCheck={onCheck}
              checkedKeys={checkedKeys}
            />
          }
        </Card>
      </Col>
    </Row>
  )
}
