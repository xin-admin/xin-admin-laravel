import XinTableV2 from '@/components/XinTableV2';
import { XinTableColumn, XinTableRef } from '@/components/XinTableV2/typings';
import XinDict from '@/components/XinDict';
import { useModel } from '@@/exports';
import ResetPsdModel from './components/ResetPsdModel';
import { IUserList } from '@/domain/iUserList';
import React, { useRef, useState } from 'react';
import UploadImgItem from '@/components/XinForm/UploadImgItem';
import { ProTableProps } from '@ant-design/pro-components';
import RechargeModel from '@/pages/User/List/components/RechargeModel';

/** 前台用户列表 */
export default () => {
  const { dictEnum } = useModel('dictModel');
  const tableRef = useRef<XinTableRef>();
  // 上传头像
  const upAvatarElement = () => (
    <UploadImgItem
      form={tableRef.current?.formRef?.current!}
      dataIndex={'avatar_id'}
      api={'user/list/avatar'}
      defaultFile={tableRef.current?.formRef?.current?.getFieldValue('avatar_url')}
      crop={true}
    />
  );
  const columns: XinTableColumn<IUserList>[] = [
    { valueType: 'digit', title: 'ID', dataIndex: 'user_id', hideInForm: true, hideInSearch: true, sorter: true },
    { valueType: 'text', title: '手机号', dataIndex: 'mobile', hideInSearch: true },
    { valueType: 'text', title: '用户名', dataIndex: 'username', hideInSearch: true },
    { valueType: 'text', title: '用户邮箱', dataIndex: 'email', hideInSearch: true },
    { valueType: 'text', title: '昵称', dataIndex: 'nickname', hideInSearch: true },
    { valueType: 'avatar', title: '头像', dataIndex: 'avatar_url', hideInSearch: true, hideInForm: true },
    {
      valueType: 'avatar',
      title: '头像',
      dataIndex: 'avatar_id',
      hideInSearch: true,
      hideInTable: true,
      renderFormItem: upAvatarElement,
    },
    {
      valueType: 'radio',
      title: '性别',
      valueEnum: dictEnum.get('sex'),
      render: (_, date) => <XinDict value={date.gender} dict={'sex'} />,
      dataIndex: 'gender',
      hideInSearch: true,
      filters: true,
    },
    { valueType: 'money', title: '余额', dataIndex: 'balance', hideInSearch: true, hideInForm: true },
    { valueType: 'textarea', title: '签名', dataIndex: 'motto', hideInSearch: true, hideInTable: true },
    {
      valueType: 'radio', title: '状态', dataIndex: 'status', hideInSearch: true, filters: true,
      valueEnum: {
        0: { text: '禁用', status: 'Error' },
        1: { text: '启用', status: 'Success' },
      },
    },
    { valueType: 'fromNow', title: '注册时间', hideInForm: true, dataIndex: 'created_at' },
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
      <XinTableV2<IUserList>
        api={'/user/list'}
        columns={columns}
        accessName={'user.list'}
        rowKey={'user_id'}
        deleteShow={false}
        afterOperateRender={(user) => [
          <RechargeModel
            user={user}
            key={'recharge'}
            afterFn={() => tableRef.current?.tableRef?.current?.reloadAndRest?.()}
          />,
          <ResetPsdModel key={'resetPassword'} user={user} />,
        ]}
        tableRef={tableRef}
        tableProps={tableProps}
        formProps={{ grid: true, colProps: { span: 12 } }}
      />
    </>
  );
}
