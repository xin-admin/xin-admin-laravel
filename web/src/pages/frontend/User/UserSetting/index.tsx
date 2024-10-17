import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import { Link, useModel } from '@@/exports';
import { Card, message } from 'antd';
import UploadImgItem from "@/components/XinForm/UploadImgItem";
import { BetaSchemaForm } from '@ant-design/pro-components';
import React from 'react';
import { setUserInfo } from '@/services/api/user';
import UserLayout from '../components/UserLayout';


const Table : React.FC = () => {

  const {initialState} = useModel('@@initialState');

  const columns: ProFormColumnsAndProColumns<USER.UserInfo>[] = [
    {
      title: '用户名',
      dataIndex: 'username',
      valueType: 'text',
      formItemProps: { rules: [{required: true}] }
    },
    {
      title: '昵称',
      dataIndex: 'nickname',
      valueType: 'text',
      formItemProps: { rules: [{required: true}] }
    },
    {
      title: '性别',
      dataIndex: 'gender',
      valueType: 'radio',
      valueEnum: new Map([
        ['0','男'],
        ['1','女'],
        ['2','保密']
      ])
    },
    {
      title: '邮箱',
      dataIndex: 'email',
      valueType: 'text',
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
          api={'api/user/upAvatar'}
          defaultFile={form.getFieldValue('avatar_url')}
          crop={true}
        />
      },
      colProps: {md: 12,},
    },
    {
      title: '手机号',
      dataIndex: 'mobile',
      valueType: 'text',
      formItemProps: { rules: [{required: true},{}] }
    }
  ];


  return (
    <UserLayout>
      <Card title={'账户设置'}  extra={<Link to={'/user/setPassword'}>修改密码</Link>}>
        <BetaSchemaForm<USER.UserInfo>
          layoutType="Form"
          layout={'horizontal'}
          labelCol={{span: 2}}
          wrapperCol={{span: 6}}
          onFinish={async (values) => {
            console.log(values);
            await setUserInfo(values);
            message.success('更新成功');
          }}
          initialValues={initialState!.currentUser}
          columns={columns}
        />
      </Card>
    </UserLayout>

  )

}

export default Table
