import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import {useModel} from "@@/exports";
import { Card, message } from 'antd';
import { BetaSchemaForm } from '@ant-design/pro-components';
import React from 'react';
import { setPassWord } from '@/services/api/user';
import UserLayout from '@/pages/frontend/User/components/UserLayout';
import { Link } from '@umijs/max';


const Table : React.FC = () => {

  const {initialState} = useModel('@@initialState');


  const columns: ProFormColumnsAndProColumns<USER.UpdatePassword>[] = [
    {
      title: '旧密码',
      dataIndex: 'oldPassword',
      valueType: 'password',
      formItemProps: { rules: [{required: true}] }
    },
    {
      title: '新密码',
      dataIndex: 'newPassword',
      valueType: 'password',
      formItemProps: { rules: [{required: true}] }
    },
    {
      title: '重复密码',
      dataIndex: 'rePassword',
      valueType: 'password',
      formItemProps: { rules: [
        {
          required: true,
        },
        ({ getFieldValue }) => ({
          validator(_, value) {
            if (!value || getFieldValue('newPassword') === value) {
              return Promise.resolve();
            }
            return Promise.reject(new Error('重复密码不同!'));
          },
        }),
      ]}
    }
  ];

  return (
    <UserLayout>
      <Card title={'修改密码'} extra={<Link to="/user/userSetting">编辑资料</Link>}>
        <BetaSchemaForm<USER.UpdatePassword>
          layoutType="Form"
          onFinish={async (values) => {
            console.log(values);
            await setPassWord(values);
            message.success('更新成功');
          }}
          layout={'horizontal'}
          labelCol={{span: 2}}
          wrapperCol={{span: 6}}
          initialValues={initialState!.currentUser}
          columns={columns}
        />
      </Card>
    </UserLayout>
  )

}

export default Table
