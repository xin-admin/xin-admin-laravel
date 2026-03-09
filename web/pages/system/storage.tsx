import React, { useEffect, useRef, useState } from 'react';
import {
  Button,
  Card,
  Form,
  message,
  Row,
  Col,
  Typography,
  Divider,
  Select
} from 'antd';
import { CloudUploadOutlined, CheckCircleOutlined } from '@ant-design/icons';
import { getStorageConfig, saveStorageConfig, testStorageConnection } from '@/api/system/sysStorage.ts';
import type { FormColumn } from "@/components/XinFormField/FieldRender";
import XinForm, { type XinFormRef } from "@/components/XinForm";
const { Text, Title } = Typography;

interface StorageResponse {
  default: string;
  local: {
    root: string;
    url: string;
    visibility: string;
  };
  s3: {
    key: string;
    secret: string;
    region: string;
    bucket: string;
    url: string;
    endpoint: string;
    use_path_style_endpoint: boolean;
  };
  ftp: {
    host: string;
    username: string;
    password: string;
    port: number;
    root: string;
    passive: boolean;
    ssl: boolean;
    timeout: number;
  };
  sftp: {
    host: string;
    username: string;
    password: string;
    port: number;
    root: string;
    timeout: number;
    private_key: string;
    passphrase: string;
  };
}

const StorageConfig: React.FC = () => {
  const formRef = useRef<XinFormRef>(null);
  const [form] = Form.useForm();
  const [testing, setTesting] = useState(false);
  const [selectedDisk, setSelectedDisk] = useState<string>('local');

  const driverOptions = [
    { label: '本地存储 (Local)', value: 'local' },
    { label: 'Amazon S3 / 对象存储', value: 's3' },
    { label: 'FTP 服务器', value: 'ftp' },
    { label: 'SFTP 服务器', value: 'sftp' },
  ];

  const columns: FormColumn<StorageResponse>[] = [
    {
      dataIndex: 'default',
      valueType: 'radioButton',
      title: '默认存储驱动',
      fieldProps: {
        options: driverOptions
      },
      required: true,
      colProps: { span: 24 }
    },
    // 本地存储配置
    {
      fieldRender: () => <Divider>本地存储配置</Divider>,
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'local'
      },
      colProps: { span: 24 },
      noStyle: true
    },
    {
      dataIndex: ['local', 'url'],
      valueType: 'text',
      title: '访问 URL',
      fieldProps: {
        placeholder: '例如: https://example.com/storage',
      },
      tooltip: '文件的公开访问 URL 前缀',
      colProps: { span: 24 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'local'
      }
    },
    // S3 配置
    {
      fieldRender: () => <Divider>S3 / 对象存储配置</Divider>,
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      },
      colProps: { span: 24 },
      noStyle: true
    },
    {
      dataIndex: ['s3', 'key'],
      valueType: 'text',
      title: 'Access Key ID',
      fieldProps: {
        placeholder: '请输入 Access Key ID',
      },
      required: true,
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    {
      dataIndex: ['s3', 'secret'],
      valueType: 'password',
      title: 'Secret Access Key',
      fieldProps: {
        placeholder: '请输入 Secret Access Key',
      },
      required: true,
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    {
      dataIndex: ['s3', 'region'],
      valueType: 'text',
      title: '区域 (Region)',
      fieldProps: {
        placeholder: '例如: us-east-1 或 cn-hangzhou',
      },
      required: true,
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    {
      dataIndex: ['s3', 'bucket'],
      valueType: 'text',
      title: '存储桶 (Bucket)',
      fieldProps: {
        placeholder: '请输入存储桶名称',
      },
      required: true,
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    {
      dataIndex: ['s3', 'endpoint'],
      valueType: 'text',
      title: '端点 (Endpoint)',
      fieldProps: {
        placeholder: '自定义端点，如阿里云 OSS: oss-cn-hangzhou.aliyuncs.com',
      },
      tooltip: '使用 AWS S3 时可留空，使用阿里云 OSS、腾讯云 COS 等需要填写',
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    {
      dataIndex: ['s3', 'url'],
      valueType: 'text',
      title: '自定义 URL',
      fieldProps: {
        placeholder: '自定义文件访问 URL 前缀（可选）',
      },
      tooltip: '如果使用 CDN，可以在此配置 CDN 域名',
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    {
      dataIndex: ['s3', 'use_path_style_endpoint'],
      valueType: 'switch',
      title: '路径风格端点',
      tooltip: '某些 S3 兼容服务（如 MinIO）需要启用此选项',
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    // FTP 配置
    {
      fieldRender: () => <Divider>FTP 服务器配置</Divider>,
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      },
      colProps: { span: 24 },
      noStyle: true
    },
    {
      dataIndex: ['ftp', 'host'],
      valueType: 'text',
      title: '主机地址',
      fieldProps: {
        placeholder: '例如: ftp.example.com',
      },
      required: true,
      colProps: { span: 16 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'port'],
      valueType: 'digit',
      title: '端口',
      fieldProps: {
        placeholder: '21',
      },
      colProps: { span: 8 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'username'],
      valueType: 'text',
      title: '用户名',
      fieldProps: {
        placeholder: '请输入 FTP 用户名',
      },
      required: true,
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'password'],
      valueType: 'password',
      title: '密码',
      fieldProps: {
        placeholder: '请输入 FTP 密码',
      },
      required: true,
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'root'],
      valueType: 'text',
      title: '根目录',
      fieldProps: {
        placeholder: '例如: /public_html/uploads',
      },
      tooltip: 'FTP 服务器上的存储根目录',
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'timeout'],
      valueType: 'digit',
      title: '超时时间',
      fieldProps: {
        placeholder: '30',
        addonAfter: '秒'
      },
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'passive'],
      valueType: 'switch',
      title: '被动模式',
      tooltip: '建议开启，适用于大多数网络环境',
      colProps: { span: 8 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'ssl'],
      valueType: 'switch',
      title: 'SSL/TLS',
      tooltip: '启用 FTPS 安全连接',
      colProps: { span: 8 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    // SFTP 配置
    {
      fieldRender: () => <Divider>SFTP 服务器配置</Divider>,
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      },
      colProps: { span: 24 },
      noStyle: true
    },
    {
      dataIndex: ['sftp', 'host'],
      valueType: 'text',
      title: '主机地址',
      fieldProps: {
        placeholder: '例如: sftp.example.com',
      },
      required: true,
      colProps: { span: 16 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'port'],
      valueType: 'digit',
      title: '端口',
      fieldProps: {
        placeholder: '22',
      },
      colProps: { span: 8 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'username'],
      valueType: 'text',
      title: '用户名',
      fieldProps: {
        placeholder: '请输入 SFTP 用户名',
      },
      required: true,
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'password'],
      valueType: 'password',
      title: '密码',
      fieldProps: {
        placeholder: '请输入 SFTP 密码',
      },
      tooltip: '如果使用密钥认证可留空',
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'root'],
      valueType: 'text',
      title: '根目录',
      fieldProps: {
        placeholder: '例如: /var/www/uploads',
      },
      tooltip: 'SFTP 服务器上的存储根目录',
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'timeout'],
      valueType: 'digit',
      title: '超时时间',
      fieldProps: {
        placeholder: '30',
        addonAfter: '秒'
      },
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'private_key'],
      valueType: 'textarea',
      title: '私钥内容',
      fieldProps: {
        placeholder: '粘贴 SSH 私钥内容（可选）',
        rows: 4
      },
      tooltip: '如果使用密钥认证，请粘贴私钥内容',
      colProps: { span: 24 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'passphrase'],
      valueType: 'password',
      title: '私钥密码',
      fieldProps: {
        placeholder: '如果私钥有密码保护，请输入',
      },
      tooltip: '私钥的密码短语（如果有）',
      colProps: { span: 24 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    }
  ];

  useEffect(() => {
    loadConfig();
  }, []);

  const loadConfig = async () => {
    const { data } = await getStorageConfig();
    if (data.success) {
      const configData = data.data as StorageResponse;
      form.setFieldsValue(configData);
      setSelectedDisk(configData.default || 'local');
    }
  };

  const onFinish = async (values: any) => {
    await saveStorageConfig(values);
    message.success('保存成功');
  };

  const onValuesChange = (changedValues: any) => {
    if (changedValues.default) {
      setSelectedDisk(changedValues.default);
    }
  };

  const onTestConnection = async () => {
    try {
      setTesting(true);
      await testStorageConnection(selectedDisk);
      message.success('连接测试成功');
    } finally {
      setTesting(false);
    }
  };

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>文件存储配置</Title>
        <Text type="secondary">配置系统文件存储方式，支持本地存储、S3 对象存储、FTP 及 SFTP 等多种存储方式</Text>
      </div>
      <Row gutter={24}>
        <Col span={16}>
          <Card variant={'borderless'}>
            <XinForm
              columns={columns}
              layout="vertical"
              formRef={formRef}
              form={form}
              grid={true}
              onFinish={onFinish}
              onValuesChange={onValuesChange}
              rowProps={{
                gutter: [10, 0]
              }}
            />
          </Card>
        </Col>

        <Col span={8}>
          <Card title="连接测试">
            <Form layout="vertical">
              <Form.Item label="当前存储驱动">
                <Select
                  value={selectedDisk}
                  onChange={setSelectedDisk}
                  options={driverOptions}
                />
              </Form.Item>

              <Button 
                type="primary" 
                block 
                icon={<CheckCircleOutlined />} 
                loading={testing}
                onClick={onTestConnection}
              >
                测试连接
              </Button>
            </Form>

            <Divider />

            <div style={{ fontSize: 12, color: '#666' }}>
              <Title level={5}><CloudUploadOutlined /> 存储驱动说明</Title>
              <p><strong>本地存储：</strong>文件存储在服务器本地磁盘，适合小型项目。</p>
              <p><strong>S3 / 对象存储：</strong>兼容 AWS S3、阿里云 OSS、腾讯云 COS、七牛云等，适合大规模文件存储。</p>
              <p><strong>FTP：</strong>通过 FTP 协议连接远程服务器存储文件。</p>
              <p><strong>SFTP：</strong>通过 SSH 安全连接远程服务器，更安全可靠。</p>
            </div>
          </Card>
        </Col>
      </Row>
    </>
  );
};

export default StorageConfig;
