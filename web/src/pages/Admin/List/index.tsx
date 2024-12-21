import {ProFormColumnsAndProColumns} from '@/components/XinTable/typings';
import XinDict from "@/components/XinDict";
import {useModel} from '@umijs/max';
import { Avatar, message, Popconfirm, Space, Tag } from 'antd';
import UploadImgItem from "@/components/XinForm/UploadImgItem";
import React, {useRef} from 'react';
import UpdatePassword from './components/UpdatePassword';
import {deleteApi, listApi} from '@/services/common/table';
import { ActionType, ProTable } from '@ant-design/pro-components';
import ButtonAccess from '@/components/ButtonAccess';
import CreateFormRender from '@/pages/Admin/List/components/CreateFormRender';
import XinTableV2 from '@/components/XinTableV2';
export interface AdminListType {
  user_id?: number
  username?: string
  nickname?: string
  avatar?: string
  avatar_url?: string
  email?: string
  mobile?: string
  status?: number
  group_id?: number
  sex?: number
  role_name?: string
  create_time?: string
  update_time?: string
}

const Table: React.FC = () => {

  const {dictEnum} = useModel('dictModel')

  const actionRef = useRef<ActionType>();

  // 删除
  const handleRemove = async (record: AdminListType) => {
    let res = await deleteApi('/admin/list', {id: record.user_id})
    if (res.success) {
      message.success('删除成功');
      actionRef.current?.reloadAndRest?.();
    } else {
      message.warning(res.msg);
    }
  }

  const columns: ProFormColumnsAndProColumns<AdminListType>[] = [
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
        placeholder: '输入ID\\账号\\昵称\\手机号\\邮箱搜索'
      }
    },
    {
      title: '用户名',
      dataIndex: 'username',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: {rules: [{required: true, message: '该项为必填'}]},
      align: 'center',
    },
    {
      title: '昵称',
      dataIndex: 'nickname',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: {rules: [{required: true, message: '该项为必填'}]},
      colProps: {md: 7,},
      align: 'center',
    },
    {
      title: '性别',
      dataIndex: 'sex',
      valueType: 'radio',
      valueEnum: dictEnum.get('sex'),
      render: (_, date) => <XinDict value={date.sex} dict={'sex'}/>,
      colProps: {md: 6,},
      filters: true,
      hideInSearch: true,
      align: 'center',
    },
    {
      title: '邮箱',
      dataIndex: 'email',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: {rules: [{required: true, message: '该项为必填'}]},
      colProps: {md: 6,},
      align: 'center',
    },
    {
      title: '管理员角色',
      dataIndex: 'role_id',
      valueType: 'select',
      formItemProps: {
        rules: [{required: true, message: '该项为必填'}],
      },
      render: (_, record) => <Tag color="processing">{record.role_name}</Tag>,
      fieldProps: {
        fieldNames: {label: 'name', value: 'role_id' },
      },
      request: async () => {
        let res = await listApi('/admin/role')
        return res.data.data
      },
      colProps: {md: 6,},
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
      formItemProps: {rules: [{required: true, message: '该项为必填'}]},
      colProps: {md: 6,},
      filters: true,
      hideInSearch: true,
      align: 'center',
    },
    {
      title: '手机号',
      dataIndex: 'mobile',
      valueType: 'text',
      hideInSearch: true,
      formItemProps: {rules: [{required: true, message: '该项为必填'}]},
      colProps: {md: 6,},
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
        />
      },
      colProps: {md: 12,},
    },
    {
      valueType: 'dependency',
      hideInTable: true,
      hideInSearch: true,
      name: ['id'],
      columns: ({id}) => {
        if (!id) {
          return [
            {
              title: '密码',
              dataIndex: 'password',
              valueType: 'password',
              formItemProps: {rules: [{required: true, message: '该项为必填'}]},
              colProps: {md: 6,},
            },
            {
              title: '确认密码',
              dataIndex: 'rePassword',
              valueType: 'password',
              formItemProps: {rules: [{required: true, message: '该项为必填'}]},
              colProps: {md: 6,},
            },
          ]
        }
        return []
      }
    },
    {
      valueType: 'fromNow',
      title: '创建时间',
      hideInForm: true,
      dataIndex: 'created_at',
    },
    {
      title: '操作栏',
      hideInForm: true,
      hideInSearch: true,
      render: (_, record) => {
        if(record.user_id === 1) return <></>
        return (
          <Space>
            <ButtonAccess auth={'admin.list.resetPassword'}>
              <UpdatePassword record={record}></UpdatePassword>
            </ButtonAccess>
            <ButtonAccess auth={'admin.list.delete'}>
              <Popconfirm
                title="Delete the task"
                description="你确定要删除这条数据吗？"
                onConfirm={() => handleRemove(record)}
                okText="确认"
                cancelText="取消"
              >
                <a>删除</a>
              </Popconfirm>
            </ButtonAccess>
          </Space>
        )
      }
    }
  ];

  return (
    <>
      <XinTableV2
        api={'/admin/list'}
        columns={columns}
        rowKey={'user_id'}
        accessName={'admin.list'}
        title={'管理员列表'}
      />
      {/*<ProTable<AdminListType>*/}
      {/*  headerTitle={'管理员列表'}*/}
      {/*  columns={columns}*/}
      {/*  actionRef={actionRef}*/}
      {/*  rowKey={'user_id'}*/}
      {/*  toolBarRender={() => [*/}
      {/*    <ButtonAccess auth={'admin.list.add'}>*/}
      {/*      <CreateFormRender columns={columns} actionRef={actionRef}/>*/}
      {/*    </ButtonAccess>*/}
      {/*  ]}*/}
      {/*  request={async (params, sorter, filter) => {*/}
      {/*    const { data, success } = await listApi('/admin/list', { ...params, sorter, filter });*/}
      {/*    return { ...data, success, }*/}
      {/*  }}*/}
      {/*/>*/}
    </>
  )
}

export default Table
