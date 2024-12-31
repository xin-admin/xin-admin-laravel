import XinDict from '@/components/XinDict';
import { useModel } from '@umijs/max';
import { Avatar, Tag } from 'antd';
import UploadImgItem from '@/components/XinForm/UploadImgItem';
import React, { useRef, useState } from 'react';
import UpdatePassword from './components/UpdatePassword';
import ButtonAccess from '@/components/ButtonAccess';
import XinTableV2 from '@/components/XinTableV2';
import { XinTableColumn, XinTableRef } from '@/components/XinTableV2/typings';
import { IAdminUserList } from '@/domain/adminList';
import { listApi } from '@/services/common/table';

const Table: React.FC = () => {
  const { dictEnum } = useModel('dictModel');
  const tableRef = useRef<XinTableRef>();
  const columns: XinTableColumn<IAdminUserList>[] = [
    {
      title: '用户ID',
      dataIndex: 'user_id',
      hideInForm: true,
      hideInSearch: true,
      sorter: true,
      align: 'center',
    },
    {
      title: '用户名',
      dataIndex: 'username',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
    },
    {
      title: '昵称',
      dataIndex: 'nickname',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { md: 7 },
    },
    {
      title: '性别',
      dataIndex: 'sex',
      valueType: 'radio',
      valueEnum: dictEnum.get('sex'),
      render: (_, date) => <XinDict value={date.sex} dict={'sex'} />,
      filters: true,
      hideInSearch: true,
    },
    {
      title: '邮箱',
      dataIndex: 'email',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { md: 6 },
    },
    {
      title: '管理员角色',
      dataIndex: 'role_id',
      valueType: 'select',
      formItemProps: {
        rules: [{ required: true, message: '该项为必填' }],
      },
      render: (_, record) => <Tag color="processing">{record.role_name}</Tag>,
      fieldProps: {
        fieldNames: { label: 'name', value: 'role_id' },
      },
      request: async () => {
        let res = await listApi('/admin/role');
        return res.data.data;
      },
    },
    {
      title: '管理员部门',
      dataIndex: 'dept_id',
      valueType: 'treeSelect',
      render: (_, record) => <Tag color="processing">{record.dept_name}</Tag>,
      request: async  () => {
        let data = await listApi('/admin/dept');
        return data.data.data
      },
      fieldProps: { fieldNames: { label: 'name', value: 'dept_id' } }
    },
    {
      title: '状态',
      dataIndex: 'status',
      valueType: 'radioButton',
      valueEnum: {
        0: { text: '禁用', status: 'Error' },
        1: { text: '启用', status: 'Success' },
      },
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      filters: true,
      hideInSearch: true,
      align: 'center',
    },
    {
      title: '手机号',
      dataIndex: 'mobile',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      align: 'center',
    },
    {
      title: '头像',
      dataIndex: 'avatar_url',
      hideInSearch: true,
      valueType: 'avatar',
      hideInForm: true,
      render: (dom, entity) => <Avatar size={'small'} src={entity.avatar_url}></Avatar>,
      align: 'center',
    },
    {
      title: '头像',
      dataIndex: 'avatar_id',
      hideInSearch: true,
      valueType: 'avatar',
      hideInTable: true,
      renderFormItem: () => {
        return <UploadImgItem
          form={tableRef.current?.formRef?.current!}
          dataIndex={'avatar_id'}
          api={'admin/upload/avatar'}
          defaultFile={tableRef.current?.formRef?.current?.getFieldValue('avatar_url')}
          crop={true}
        />;
      },
    },
    {
      valueType: 'dependency',
      hideInTable: true,
      hideInSearch: true,
      name: ['user_id'],
      columns: ({ user_id }) => {
        if (!user_id) {
          return [
            {
              title: '密码',
              dataIndex: 'password',
              valueType: 'password',
              formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
              fieldProps: { autoComplete: '' },
            },
            {
              title: '确认密码',
              dataIndex: 'rePassword',
              valueType: 'password',
              formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
              fieldProps: { autoComplete: '' },
            },
          ];
        }
        return [];
      },
    },
    {
      valueType: 'fromNow',
      title: '创建时间',
      hideInForm: true,
      dataIndex: 'created_at',
    },
    {
      valueType: 'fromNow',
      title: '更新时间',
      hideInForm: true,
      dataIndex: 'updated_at',
    },
  ];

  const [tableParams, setParams] = useState({
    keywordSearch: '',
  });

  return (
    <XinTableV2<IAdminUserList>
      api={'/admin/list'}
      columns={columns}
      rowKey={'user_id'}
      tableRef={tableRef}
      accessName={'admin.list'}
      afterOperateRender={(record) => (
        <ButtonAccess auth={'admin.list.resetPassword'}>
          <UpdatePassword record={record}></UpdatePassword>
        </ButtonAccess>
      )}
      tableProps={{
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
        postData: (data: IAdminUserList[]) => {
          return data.map(item => {
            delete item.rules;
            return item;
          });
        },
      }}
      formProps={{
        grid: true,
        colProps: { span: 12 },
      }}
    />
  );
};

export default Table;
