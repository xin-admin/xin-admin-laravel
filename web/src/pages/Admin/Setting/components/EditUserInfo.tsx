import { BetaSchemaForm, ProFormColumnsType, ProFormInstance } from '@ant-design/pro-components';
import { IAdminUserList } from '@/domain/iAdminList';
import UploadImgItem from '@/components/Xin/XinForm/UploadImgItem';
import { updateAdmin } from '@/services/admin';
import { message } from 'antd';
import React, { useRef } from 'react';
import { useModel } from '@@/exports';

export default () => {

  const userInfo = useModel('userModel', (model) => model.userInfo);
  const formRef = useRef<ProFormInstance>();

  const columns: ProFormColumnsType<IAdminUserList>[] = [
    {
      title: '用户名',
      dataIndex: 'username',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      fieldProps: { disabled: true },
      colProps: { span: 12 },
    },
    {
      title: '昵称',
      dataIndex: 'nickname',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { span: 12 },
    },
    {
      title: '邮箱',
      dataIndex: 'email',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { span: 12 },
    },
    {
      title: '手机号',
      dataIndex: 'mobile',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { span: 12 },
    },
    {
      title: '头像',
      dataIndex: 'avatar_id',
      valueType: 'avatar',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      renderFormItem: (schema, config, form) => {
        return <UploadImgItem
          form={form}
          dataIndex={'avatar_id'}
          api={'/admin/uploadAvatar'}
          defaultFile={form.getFieldValue('avatar_url')}
          crop={true}
        />;
      },
      colProps: { span: 24 },
    },
  ];
  const submit = async (value: any) => {
    await updateAdmin(value);
    message.success('更新成功');
  };


  return (
    <BetaSchemaForm<IAdminUserList>
      columns={columns}
      title={'编辑资料'}
      trigger={<a>编辑资料</a>}
      layoutType={'ModalForm'}
      initialValues={{
        username: userInfo?.username,
        nickname: userInfo?.nickname,
        email: userInfo?.email,
        mobile: userInfo?.mobile,
        avatar_url: userInfo?.avatar_url,
        avatar_id: userInfo?.avatar_id,
      }}
      rowProps={{ gutter: [20, 0] }}
      formRef={formRef}
      onFinish={submit}
      grid
    />
  )
}