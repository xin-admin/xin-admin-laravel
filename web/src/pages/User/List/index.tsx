import XinTable from '@/components/Xin/XinTable';
import { XinTableColumn, XinTableRef } from '@/components/Xin/XinTable/typings';
import ResetPsdModel from './components/ResetPsdModel';
import { IUserList } from '@/domain/iUserList';
import React, { useRef, useState } from 'react';
import { ProTableProps } from '@ant-design/pro-components';

/** 前台用户列表 */
export default () => {
  const tableRef = useRef<XinTableRef>();

  const columns: XinTableColumn<IUserList>[] = [
    { valueType: 'digit', title: 'ID', dataIndex: 'user_id', hideInForm: true, hideInSearch: true, sorter: true },
    { valueType: 'text', title: '用户名', dataIndex: 'username', hideInSearch: true },
    { valueType: 'text', title: '用户邮箱', dataIndex: 'email', hideInSearch: true },
    { valueType: 'text', title: '昵称', dataIndex: 'nickname', hideInSearch: true },
    { valueType: 'fromNow', title: '注册时间', hideInForm: true, dataIndex: 'created_at' },
    { valueType: 'fromNow', title: '更新时间', hideInForm: true, dataIndex: 'updated_at' },
  ];

  // 表格配置
  const [tableParams, setParams] = useState<{ keywordSearch?: string; }>();
  const tableProps: ProTableProps<IUserList, any> = {
    params: tableParams,
    search: false,
    toolbar: {
      search: {
        placeholder: '请输入昵称、账户、手机号搜索',
        style: { width: 304 },
        onSearch: (value: string) => {
          setParams({ keywordSearch: value });
        },
      },
      settings: [],
    },
  };

  return (
    <>
      <XinTable<IUserList>
        api={'/user/list'}
        columns={columns}
        accessName={'user.list'}
        rowKey={'user_id'}
        deleteShow={false}
        afterOperateRender={(user) => [
          <ResetPsdModel key={'resetPassword'} user={user} />,
        ]}
        tableRef={tableRef}
        tableProps={tableProps}
        formProps={{ grid: true, colProps: { span: 12 } }}
      />
    </>
  );
}
