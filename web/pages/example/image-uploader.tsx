import React, { useState } from 'react';
import { Card, Form, Button, Space, Typography } from 'antd';
import ImageUploader from '@/components/XinFormField/ImageUploader';
import type { ISysFileInfo } from '@/domain/iSysFile';
const { Title, Paragraph } = Typography;
/**
 * 图片上传器示例页面
 */
const ImageUploaderExample: React.FC = () => {
  const [form] = Form.useForm();
  const [singleValue, setSingleValue] = useState<ISysFileInfo | null>(null);
  const [multipleValue, setMultipleValue] = useState<ISysFileInfo[]>([]);

  // 提交表单
  const handleSubmit = async () => {
    try {
      const values = await form.validateFields();
      console.log('Form values:', values);
      console.log('Single image:', singleValue);
      console.log('Multiple images:', multipleValue);
    } catch (error) {
      console.error('Validation failed:', error);
    }
  };

  // 重置表单
  const handleReset = () => {
    form.resetFields();
    setSingleValue(null);
    setMultipleValue([]);
  };

  return (
    <div>
      <Typography style={{ margin: '12px 0 24px 0' }}>
        <Title level={2}>图片上传组件示例</Title>
        <Paragraph>
          基于 Ant Design Upload 封装的图片上传组件，支持图片剪裁、多张图片上传、尺寸限制、禁用状态等。
        </Paragraph>
      </Typography>
      <Card title="基础用法" style={{ marginBottom: 24 }}>
        <Form form={form} layout="vertical">
          <Form.Item
            label="单张图片上传"
            name="avatar"
            extra="支持单张图片上传，最大 5MB，尺寸不超过 1920x1080"
          >
            <ImageUploader
              action="/sys/file/list/upload/image"
              mode="single"
              value={singleValue}
              onChange={(value) => setSingleValue(value as ISysFileInfo)}
            />
          </Form.Item>

          <Form.Item
            label="多张图片上传"
            name="gallery"
            extra="支持最多 5 张图片上传"
          >
            <ImageUploader
              action="/sys/file/list/upload/image"
              mode="multiple"
              maxCount={5}
              value={multipleValue}
              onChange={(value) => setMultipleValue(value as ISysFileInfo[])}
            />
          </Form.Item>

          <Form.Item>
            <Space>
              <Button type="primary" onClick={handleSubmit}>
                提交
              </Button>
              <Button onClick={handleReset}>重置</Button>
            </Space>
          </Form.Item>
        </Form>
      </Card>

      <Card title="高级配置" style={{ marginBottom: 24 }}>
        <Form layout="vertical">
          <Form.Item
            label="自定义尺寸限制"
            extra="限制图片尺寸为 800x600，大小不超过 2MB"
          >
            <ImageUploader
              action="/sys/file/list/upload/image"
              mode="single"
              maxWidth={800}
              maxHeight={600}
              maxSize={2}
            />
          </Form.Item>

          <Form.Item
            label="裁剪功能 - 自由裁剪"
            extra="启用裁剪功能，可自由调整裁剪区域"
          >
            <ImageUploader
              action="/sys/file/list/upload/image"
              mode="single"
              croppable
            />
          </Form.Item>

          <Form.Item
            label="裁剪功能 - 1:1 正方形"
            extra="固定 1:1 比例裁剪，适合头像上传"
          >
            <ImageUploader
              action="/sys/file/list/upload/image"
              mode="single"
              croppable
              cropperOptions={{ aspect: 1 }}
            />
          </Form.Item>

          <Form.Item
            label="裁剪功能 - 圆形裁剪"
            extra="圆形裁剪模式，适合头像上传"
          >
            <ImageUploader
              action="/sys/file/list/upload/image"
              mode="single"
              croppable
              cropperOptions={{ cropShape: 'round', aspect: 1 }}
            />
          </Form.Item>

          <Form.Item
            label="裁剪功能 - 16:9 宽屏"
            extra="固定 16:9 比例裁剪，适合横幅图片"
          >
            <ImageUploader
              action="/sys/file/list/upload/image"
              mode="single"
              croppable
              cropperOptions={{ aspect: 16 / 9 }}
            />
          </Form.Item>

          <Form.Item label="禁用状态">
            <ImageUploader
              action="/sys/file/list/upload/image"
              mode="single"
              disabled
            />
          </Form.Item>
        </Form>
      </Card>
    </div>
  );
};

export default ImageUploaderExample;
