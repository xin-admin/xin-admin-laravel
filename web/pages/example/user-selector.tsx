import React, { useState, useRef } from 'react';
import { Card, Space, Divider, Typography, message } from 'antd';
import XinForm, { type XinFormRef } from '@/components/XinForm';
import type { FormColumn } from '@/components/XinFormField/FieldRender/typings';
import UserSelector from '@/components/XinFormField/UserSelector';

const { Title, Paragraph, Text } = Typography;

/**
 * 用户选择器组件使用示例页面
 */
const UserSelectorExample: React.FC = () => {
  const [singleUser, setSingleUser] = useState<number | null>(null);
  const [multipleUsers, setMultipleUsers] = useState<number[]>([]);
  const formRef = useRef<XinFormRef>(undefined);

  // 表单列配置
  const columns: FormColumn<any>[] = [
    {
      dataIndex: 'taskName',
      title: '任务名称',
      valueType: 'text',
      rules: [{ required: true, message: '请输入任务名称' }],
      fieldProps: {
        placeholder: '请输入任务名称',
      },
    },
    {
      dataIndex: 'owner_id',
      title: '任务负责人',
      rules: [{ required: true, message: '请选择负责人' }],
      fieldRender: () => <UserSelector placeholder="请选择负责人" />,
    },
    {
      dataIndex: 'participant_ids',
      title: '参与人员',
      rules: [
        { required: true, message: '请至少选择一个参与人员' },
        {
          validator: (_: any, value: number[]) => {
            if (value && value.length > 10) {
              return Promise.reject('最多选择10个参与人员');
            }
            return Promise.resolve();
          },
        },
      ],
      fieldRender: () => <UserSelector mode="multiple" maxTagCount={3} placeholder="请选择参与人员" />,
    },
    {
      dataIndex: 'description',
      title: '任务描述',
      valueType: 'textarea',
      fieldProps: {
        placeholder: '请输入任务描述',
      },
    },
  ];

  return (
    <div>
      <Typography style={{ margin: '12px 0 24px 0' }}>
        <Title level={2}>用户选择器组件示例</Title>
        <Paragraph>
          基于 AntDesign 封装的用户选择器表单组件，支持单选和多选模式。
        </Paragraph>
      </Typography>

      <Space direction="vertical" size="large" style={{ width: '100%' }}>
        <Card title="独立使用" bordered>
          
          <Text>单选模式：</Text>
          <div className="mt-2">
            <UserSelector
              value={singleUser}
              onChange={(value) => {
                setSingleUser(value as number);
                message.success(`选中用户ID: ${value}`);
              }}
              placeholder="请选择用户"
            />
          </div>
          <Text type="secondary" className="mt-2 block">
            当前选中: {singleUser || '未选择'}
          </Text>

          <Divider />

          <Text>多选模式：</Text>
          <div className="mt-2">
            <UserSelector
              mode="multiple"
              value={multipleUsers}
              onChange={(value) => {
                setMultipleUsers(value as number[]);
                message.success(`选中${(value as number[]).length}个用户`);
              }}
              placeholder="请选择多个用户"
              maxTagCount={3}
            />
          </div>
          <Text type="secondary" className="mt-2 block">
            当前选中: {multipleUsers.length > 0 ? multipleUsers.join(', ') : '未选择'}
          </Text>
        </Card>

        <Card title="在 XinForm 中使用" bordered>
          <XinForm
            formRef={formRef}
            columns={columns}
            onFinish={async (values) => {
              console.log('表单提交:', values);
              message.success('提交成功！');
              message.info(`负责人ID: ${values.owner_id}, 参与人IDs: ${values.participant_ids?.join(', ')}`);
              return true;
            }}
            submitter={{
              submitText: '提交表单',
              render: (dom) => dom.submit,
            }}
          />
        </Card>
      </Space>
    </div>
  );
};

export default UserSelectorExample;
