import { Button, Form, message } from 'antd';
import { PlusOutlined } from '@ant-design/icons';
import { addGroup } from '@/services/admin/system';
import { ModalForm, ProFormText } from '@ant-design/pro-components';
import React from 'react';


export default () => {
  /**
   * 新增分组表单
   */
  const [form] = Form.useForm<{key: string, title: string}>();

  return (
    <ModalForm<{ key: string, title: string, pid?: number }>
      title='新建分组'
      trigger={<Button type={'primary'} icon={<PlusOutlined />} block>新增分组</Button>}
      form={form}
      autoFocusFirstInput
      modalProps={{ destroyOnClose: true, width: 400}}
      submitTimeout={2000}
      onFinish={async (values) => {
        await addGroup(values)
        message.success('新建成功')
        return true
      }}
    >
      <ProFormText
        name="title"
        label="分组标题"
        tooltip="最长为 24 位"
        rules={[
          {required: true, message: '此项为必填项'},
        ]}
        placeholder="请输入标题"
      />
      <ProFormText
        name="key"
        label="分组 KEY"
        placeholder="请输入KEY"
        rules={[
          {required: true, message: '此项为必填项'},
        ]}
      />
    </ModalForm>

  )
}
