import IconsItem from '@/components/XinForm/IconsItem';
import { Card, Form } from 'antd';
import { useState } from 'react';

interface Data {
  icon: string;
}

export default () => {
  const [form] = Form.useForm();
  const initialValues = useState<Data>({
    icon: '',
  });
  return (
    <Card  title={'图标选择'}>
      <Form<Data> form={form} initialValues={initialValues}>
        <Form.Item label={'图标选择'} name='icon' style={{ width: 300 }}>
          <IconsItem form={form} dataIndex={'icon'} value={form.getFieldValue('icon')}></IconsItem>
        </Form.Item>
        <Form.Item label={'图标选择2'} name='icon2' style={{ width: 300 }}>
          <IconsItem form={form} dataIndex={'icon2'} value={form.getFieldValue('icon2')}></IconsItem>
        </Form.Item>
        <Form.Item label={'图标选择3'} name='icon3' style={{ width: 300 }}>
          <IconsItem form={form} dataIndex={'icon3'} value={form.getFieldValue('icon3')}></IconsItem>
        </Form.Item>
        <Form.Item label={'图标选择4'} name='icon4' style={{ width: 300 }}>
          <IconsItem form={form} dataIndex={'icon4'} value={form.getFieldValue('icon4')}></IconsItem>
        </Form.Item>
      </Form>
    </Card>
  );
}
