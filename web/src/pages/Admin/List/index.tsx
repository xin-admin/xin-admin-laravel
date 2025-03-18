import XinDict from '@/components/Xin/XinDict';
import { useModel } from '@umijs/max';
import { Avatar,  Col, Row, Tag, Tree } from 'antd';
import UploadImgItem from '@/components/Xin/XinForm/UploadImgItem';
import React, { useEffect, useRef, useState } from 'react';
import UpdatePassword from './components/UpdatePassword';
import ButtonAccess from '@/components/Access/ButtonAccess';
import XinTable from '@/components/Xin/XinTable';
import { XinTableColumn, XinTableRef } from '@/components/Xin/XinTable/typings';
import { IAdminUserList } from '@/domain/iAdminList';
import { listApi } from '@/services/common/table';
import { ProCard, ProTableProps } from '@ant-design/pro-components';
import { IDept } from '@/domain/iDept';
import { DownOutlined } from '@ant-design/icons';

const Table: React.FC = () => {
  const { dictEnum } = useModel('dictModel');
  const tableRef = useRef<XinTableRef>();
  const [deptData, setDeptData] = useState()
  const columns: XinTableColumn<IAdminUserList>[] = [
    { title: '用户ID', dataIndex: 'user_id', hideInForm: true, hideInSearch: true, sorter: true, align: 'center', },
    { title: '用户名', dataIndex: 'username', valueType: 'text', hideInSearch: true,
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
    },
    { title: '昵称', dataIndex: 'nickname', valueType: 'text', hideInSearch: true, colProps: { md: 7 },
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
    },
    { title: '性别', dataIndex: 'sex', valueType: 'radio', filters: true, hideInSearch: true,
      valueEnum: dictEnum.get('sex'),
      render: (_, date) => <XinDict value={date.sex} dict={'sex'} />,
    },
    { title: '邮箱', dataIndex: 'email', valueType: 'text', hideInSearch: true, colProps: { md: 6 },
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
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
      request: async  () => deptData ? deptData : [],
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
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      renderFormItem: () => {
        return <UploadImgItem
          form={tableRef.current?.formRef?.current!}
          dataIndex={'avatar_id'}
          api={'/admin/uploadAvatar'}
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
    { valueType: 'fromNow', title: '创建时间', hideInForm: true, dataIndex: 'created_at', },
    { valueType: 'fromNow', title: '更新时间', hideInForm: true, dataIndex: 'updated_at', },
  ];

  const [tableParams, setParams] = useState<{
    keywordSearch?: string;
    dept_id?: string | number | bigint;
  }>();

  const tableProps: ProTableProps<IDept, any> = {
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
    cardProps: {
      bordered: true
    },
    postData: (data: IAdminUserList[]) => {
      return data.map(item => {
        delete item.rules;
        return item;
      });
    },
  }

  useEffect(() => {
    listApi('/admin/dept').then((res) => {
      setDeptData(res.data.data);
    })
  }, [])

  return (
    <Row gutter={20}>
      <Col flex="300px">
        <ProCard title={'部门'} bordered={true} loading={!deptData}>
          {deptData && (
            <Tree
              showLine
              defaultExpandAll
              switcherIcon={<DownOutlined />}
              onSelect={(value) => setParams({ dept_id: value[0] })}
              treeData={deptData}
              fieldNames={{key: 'dept_id', title: 'name'}}
            />
          )}
        </ProCard>
      </Col>
      <Col flex="auto">
        <XinTable<IAdminUserList>
          api={'/admin/list'}
          columns={columns}
          rowKey={'user_id'}
          tableRef={tableRef}
          accessName={'admin.list'}
          afterOperateRender={(record) => (
            <>
              {record.user_id !== 1 &&
                <ButtonAccess auth={'admin.list.resetPassword'}>
                  <UpdatePassword record={record}></UpdatePassword>
                </ButtonAccess>
              }
            </>
          )}
          editShow={(i) => i.user_id !== 1}
          deleteShow={(i) => i.user_id !== 1}
          tableProps={tableProps}
          formProps={{
            grid: true,
            colProps: { span: 12 },
          }}
        />
      </Col>
    </Row>
  );
};

export default Table;
