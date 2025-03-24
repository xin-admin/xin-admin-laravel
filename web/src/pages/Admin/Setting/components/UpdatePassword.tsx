import { editApi } from '@/services/common/table';
import { message } from 'antd';
import { BetaSchemaForm, ProFormColumnsType } from '@ant-design/pro-components';
import React from 'react';

export default function UpdatePassword() {

  const columnsPws: ProFormColumnsType<any>[] = [
    {
      title: '旧密码',
      dataIndex: 'oldPassword',
      valueType: 'password',
      formItemProps: { rules: [{required: true,message: '该项为必填'}] }
    },
    {
      title: '密码',
      dataIndex: 'newPassword',
      valueType: 'password',
      formItemProps: { rules: [{required: true,message: '该项为必填'}] }
    },
    {
      title: '确认密码',
      dataIndex: 'rePassword',
      valueType: 'password',
      formItemProps: { rules: [
          {required: true,message: '该项为必填'},
          ({ getFieldValue }) => ({
            validator(_, value) {
              if (!value || getFieldValue('newPassword') === value) {
                return Promise.resolve();
              }
              return Promise.reject(new Error('两次输入的密码不同'));
            },
          }),
        ]
      }
    },
  ]

  /**
   * 修改密码
   * @param fields
   */
  const defaultUpdate = async (fields: any) => {
    await editApi('/admin/updatePassword', fields)
    message.success('更新成功！');
  }

  return (
    <BetaSchemaForm
      title={'修改管理员密码'}
      layoutType={'Form'}
      style={{maxWidth: 400}}
      grid={ true }
      onFinish={ defaultUpdate }
      columns= { columnsPws }
    />
  );
}
