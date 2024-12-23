import XinDict from '@/components/XinDict';
import { useModel } from '@umijs/max';
import { Avatar, Tag } from 'antd';
import UploadImgItem from '@/components/XinForm/UploadImgItem';
import React from 'react';
import UpdatePassword from './components/UpdatePassword';
import { listApi } from '@/services/common/table';
import ButtonAccess from '@/components/ButtonAccess';
import XinTableV2 from '@/components/XinTableV2';
import { XinTableColumnType } from '@/components/XinTableV2/typings';

export interface AdminListType {
  user_id?: number;
  username?: string;
  nickname?: string;
  avatar?: string;
  avatar_url?: string;
  email?: string;
  mobile?: string;
  status?: number;
  group_id?: number;
  sex?: number;
  role_name?: string;
  create_time?: string;
  update_time?: string;
}

const Table: React.FC = () => {

  const { dictEnum } = useModel('dictModel');

  const columns: XinTableColumnType<AdminListType>[] = [
    {
      title: '用户ID',
      dataIndex: 'user_id',
      hideInForm: true,
      hideInSearch: true,
      sorter: true,
      align: 'center',
    },
    {
      title: '搜索用户',
      dataIndex: 'keywordSearch',
      hideInForm: true,
      hideInTable: true,
      fieldProps: {
        placeholder: '输入ID\\账号\\昵称\\手机号\\邮箱搜索',
      },
    },
    {
      title: '用户名',
      dataIndex: 'username',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      align: 'center',
    },
    {
      title: '昵称',
      dataIndex: 'nickname',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { md: 7 },
      align: 'center',
    },
    {
      title: '性别',
      dataIndex: 'sex',
      valueType: 'radio',
      valueEnum: dictEnum.get('sex'),
      render: (_, date) => <XinDict value={date.sex} dict={'sex'} />,
      filters: true,
      hideInSearch: true,
      align: 'center',
    },
    {
      title: '邮箱',
      dataIndex: 'email',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { md: 6 },
      align: 'center',
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
      align: 'center',
    },
    {
      title: '状态',
      dataIndex: 'status',
      valueType: 'radioButton',
      valueEnum: {
        0: {
          text: '禁用',
          status: 'Error',
        },
        1: {
          text: '启用',
          status: 'Success',
        },
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
      renderFormItem: (schema, config, form) => {
        return <UploadImgItem
          form={form}
          dataIndex={'avatar_id'}
          api={'admin/admin/upAvatar'}
          defaultFile={form.getFieldValue('avatar_url')}
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
            },
            {
              title: '确认密码',
              dataIndex: 'rePassword',
              valueType: 'password',
              formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
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
  ];

  return (
    <>
      <XinTableV2
        api={'/admin/list'}
        columns={columns}
        rowKey={'user_id'}
        accessName={'admin.list'}
        tableProps={{
          operate: (record) => (
            <ButtonAccess auth={'admin.list.resetPassword'}>
              <UpdatePassword record={record}></UpdatePassword>
            </ButtonAccess>
          ),
        }}
      />
    </>
  );
};

export default Table;
