import React, { useState, useRef } from 'react';
import { Card, Space, Divider, Typography, message } from 'antd';
import XinForm, { type XinFormRef } from '@/components/XinForm';
import type { FormColumn } from '@/components/XinFormField/FieldRender/typings';
import IconSelect from '@/components/XinFormField/IconSelector';

const { Title, Paragraph, Text } = Typography;

/**
 * 图标选择器组件使用示例页面
 */
const IconSelectExample: React.FC = () => {
  const [icon, setIcon] = useState<string>('');
  const formRef = useRef<XinFormRef>(undefined);

  // 表单列配置
  const columns: FormColumn<any>[] = [
    {
      dataIndex: 'systemName',
      title: '系统名称',
      valueType: 'text',
      rules: [{ required: true, message: '请输入系统名称' }],
      fieldProps: {
        placeholder: '请输入系统名称',
      },
    },
    {
      dataIndex: 'systemIcon',
      title: '系统图标',
      rules: [{ required: true, message: '请选择系统图标' }],
      fieldRender: () => <IconSelect placeholder="请选择系统图标" />,
    },
    {
      dataIndex: 'description',
      title: '系统描述',
      valueType: 'textarea',
      fieldProps: {
        placeholder: '请输入系统描述',
        rows: 4,
      },
    },
  ];

  return (
    <div>
      <Typography style={{ margin: '12px 0 24px 0' }}>
        <Title level={2}>图标选择器组件示例</Title>
        <Paragraph>
          基于 Ant Design Select + Modal + Tabs 封装的图标选择器组件，支持多分类图标选择。
        </Paragraph>
      </Typography>
      <Space direction="vertical" size="large" style={{ width: '100%' }}>
        <Card title="独立使用" bordered>

          <Text>基础用法：</Text>
          <div className="mt-2">
            <IconSelect
              value={icon}
              onChange={(value) => {
                setIcon(value || '');
                if (value) {
                  message.success(`选中图标: ${value}`);
                } else {
                  message.info('已清空图标');
                }
              }}
              placeholder="请选择图标"
            />
          </div>

          <Divider />

          <Text>禁用状态：</Text>
          <div className="mt-2">
            <IconSelect
              value="HomeOutlined"
              disabled={true}
              placeholder="禁用状态"
            />
          </div>

          <Divider />

          <Text>只读状态：</Text>
          <div className="mt-2">
            <IconSelect
              value="SettingOutlined"
              readonly={true}
              placeholder="只读状态"
            />
          </div>
          <Text type="secondary" className="mt-2 block text-sm">
            只读模式下不能打开选择弹窗，但可以清空
          </Text>
        </Card>

        <Card title="在 XinForm 中使用" bordered>
          <XinForm
            formRef={formRef}
            columns={columns}
            onFinish={async (values) => {
              console.log('XinForm 提交:', values);
              message.success('提交成功！');
              message.info(`系统图标: ${values.systemIcon}`);
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

export default IconSelectExample;
