import XinTableV2 from '@/components/XinTableV2';
import { BetaSchemaForm } from '@ant-design/pro-components';
import React from 'react';
import { IMonitor } from '@/domain/iMonitor';
import { XinTableColumn } from '@/components/XinTableV2/typings';


const Table: React.FC = () => {
  const columns: XinTableColumn<IMonitor>[] = [
    { title: 'ID', dataIndex: 'id', hideInForm: true, sorter: true, hideInSearch: true },
    { title: '接口名称', dataIndex: 'name', valueType: 'text' },
    { title: '方法', dataIndex: 'action', valueType: 'text', hideInSearch: true },
    { title: '访问IP', dataIndex: 'ip', valueType: 'text' },
    { title: '访问地址', dataIndex: 'address', valueType: 'text', hideInSearch: true },
    { title: 'HOST', dataIndex: 'host', valueType: 'text', hideInSearch: true },
    { title: '用户头像', dataIndex: ['user', 'avatar_url'], valueType: 'avatar', hideInForm: true, hideInSearch: true },
    { title: '用户昵称', dataIndex: ['user', 'nickname'], valueType: 'text', hideInForm: true, hideInSearch: true },
    { title: '用户名', dataIndex: ['user', 'username'], valueType: 'text', hideInForm: true, hideInSearch: true },
    { title: '用户ID', dataIndex: 'user_id', valueType: 'digit', hideInForm: true, hideInTable: true },
    { title: '请求地址', dataIndex: 'url', valueType: 'text', hideInSearch: true, hideInTable: true },
    { title: 'POST数据', dataIndex: 'data', valueType: 'jsonCode', hideInSearch: true, hideInTable: true },
    { title: '请求参数', dataIndex: 'params', valueType: 'jsonCode', hideInSearch: true, hideInTable: true },
    { title: '请求时间', dataIndex: 'created_at', valueType: 'fromNow', hideInSearch: true },
    { title: '请求时间', dataIndex: 'created_at', valueType: 'date', hideInTable: true, hideInForm: true },
    {
      title: '操作', hideInForm: true, hideInSearch: true,
      render: (_, record) => (
        <BetaSchemaForm<IMonitor>
          columns={columns}
          readonly
          initialValues={record}
          layoutType="ModalForm"
          trigger={<a>详情</a>}
          layout="horizontal"
          labelCol={{ span: 4 }}
          submitter={false}
        />
      ),
    },
  ];

  return (
    <XinTableV2<IMonitor>
      api={'/system/monitor'}
      columns={columns}
      rowKey={'id'}
      addShow={false}
      operateShow={false}
      accessName={'system.monitor'}
    />
  );
};

export default Table;
