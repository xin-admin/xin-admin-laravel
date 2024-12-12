import XinTable from '@/components/XinTable'
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import { BetaSchemaForm } from '@ant-design/pro-components';
import { Avatar, Space, Tooltip } from 'antd';
import React from 'react';

const api = '/monitor';

interface Data {
  id?: number
  name?: string
  controller?: string
  action?: string
  ip?: string
  host?: string
  url?: string
  data?: string
  params?: string
  user_id?: number
  address?: string
  user?: USER.AdminInfo
  create_time?: string
}

const Table: React.FC = () => {
  const columns: ProFormColumnsAndProColumns<Data>[] = [
    {
      title: 'ID',
      dataIndex: 'id',
      hideInForm: true,
      sorter: true,
      hideInSearch: true
    },
    {
      title: '接口名称',
      dataIndex: 'name',
      valueType: 'text',
    },
    {
      title: '方法',
      dataIndex: 'action',
      valueType: 'text',
      hideInSearch: true
    },
    {
      title: '访问IP',
      dataIndex: 'ip',
      valueType: 'text'
    },
    {
      title: '访问地址',
      dataIndex: 'address',
      valueType: 'text',
      hideInSearch: true,
    },
    {
      title: 'HOST',
      dataIndex: 'host',
      valueType: 'text',
      hideInSearch: true,
    },
    {
      title: '请求用户',
      dataIndex: 'user',
      valueType: 'text',
      hideInForm: true,
      hideInSearch: true,
      render: (_, record) => {
        return (
          <>
            <Tooltip title={<>ID: {record.user_id}</>}>
              <Space>
                <Avatar src={record.user?.avatar_url}></Avatar>
                {record.user?.nickname}
              </Space>
            </Tooltip>
          </>
        )
      }
    },
    {
      title: '请求用户ID',
      dataIndex: 'user_id',
      valueType: 'digit',
      hideInForm: true,
      hideInTable: true,
    },
    {
      title: '请求地址',
      dataIndex: 'url',
      valueType: 'text',
      hideInSearch: true,
      hideInTable: true
    },
    {
      title: 'POST数据',
      dataIndex: 'data',
      valueType: 'jsonCode',
      hideInSearch: true,
      hideInTable: true
    },
    {
      title: '请求参数',
      dataIndex: 'params',
      valueType: 'jsonCode',
      hideInSearch: true,
      hideInTable: true
    },
    {
      title: '请求时间',
      dataIndex: 'created_at',
      valueType: 'fromNow',
      hideInSearch: true,
    },
    {
      title: '请求时间',
      dataIndex: 'created_at',
      valueType: 'date',
      hideInTable: true,
      hideInForm: true,
    },
    {
      title: '操作',
      render: (_, record) => {
        return (
          <BetaSchemaForm<Data>
            columns={columns}
            readonly
            initialValues={record}
            layoutType='ModalForm'
            trigger={<a>详情</a>}
            layout='horizontal'
            labelCol={{ span: 4 }}
            submitter={false}
          />
        )
      },
      hideInForm: true,
      hideInSearch: true,
    }
  ];

  return (
    <>
      <XinTable<Data>
        tableApi={api}
        columns={columns}
        options={{
          density: true,
          search: true,
          fullScreen: true,
          setting: true,
        }}
        pagination={{
          showSizeChanger: true,
        }}
        addShow={false}
        operateShow={false}
        accessName={'system.dict'}
      />
    </>

  )
}

export default Table
