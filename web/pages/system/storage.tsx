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
import { useTranslation } from 'react-i18next';
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
  const { t } = useTranslation();
  const formRef = useRef<XinFormRef>(null);
  const [form] = Form.useForm();
  const [testing, setTesting] = useState(false);
  const [selectedDisk, setSelectedDisk] = useState<string>('local');

  const driverOptions = [
    { label: t('system.storage.driver.local'), value: 'local' },
    { label: t('system.storage.driver.s3'), value: 's3' },
    { label: t('system.storage.driver.ftp'), value: 'ftp' },
    { label: t('system.storage.driver.sftp'), value: 'sftp' },
  ];

  const columns: FormColumn<StorageResponse>[] = [
    {
      dataIndex: 'default',
      valueType: 'radioButton',
      title: t('system.storage.driver'),
      fieldProps: {
        options: driverOptions
      },
      required: true,
      colProps: { span: 24 }
    },
    // 本地存储配置
    {
      fieldRender: () => <Divider>{t('system.storage.local.title')}</Divider>,
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
      title: t('system.storage.local.url'),
      fieldProps: {
        placeholder: t('system.storage.local.url.placeholder'),
      },
      tooltip: t('system.storage.local.url.tooltip'),
      colProps: { span: 24 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'local'
      }
    },
    // S3 配置
    {
      fieldRender: () => <Divider>{t('system.storage.s3.title')}</Divider>,
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
      title: t('system.storage.s3.key'),
      fieldProps: {
        placeholder: t('system.storage.s3.key.placeholder'),
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
      title: t('system.storage.s3.secret'),
      fieldProps: {
        placeholder: t('system.storage.s3.secret.placeholder'),
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
      title: t('system.storage.s3.region'),
      fieldProps: {
        placeholder: t('system.storage.s3.region.placeholder'),
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
      title: t('system.storage.s3.bucket'),
      fieldProps: {
        placeholder: t('system.storage.s3.bucket.placeholder'),
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
      title: t('system.storage.s3.endpoint'),
      fieldProps: {
        placeholder: t('system.storage.s3.endpoint.placeholder'),
      },
      tooltip: t('system.storage.s3.endpoint.tooltip'),
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    {
      dataIndex: ['s3', 'url'],
      valueType: 'text',
      title: t('system.storage.s3.url'),
      fieldProps: {
        placeholder: t('system.storage.s3.url.placeholder'),
      },
      tooltip: t('system.storage.s3.url.tooltip'),
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    {
      dataIndex: ['s3', 'use_path_style_endpoint'],
      valueType: 'switch',
      title: t('system.storage.s3.path_style'),
      tooltip: t('system.storage.s3.path_style.tooltip'),
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 's3'
      }
    },
    // FTP 配置
    {
      fieldRender: () => <Divider>{t('system.storage.ftp.title')}</Divider>,
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
      title: t('system.storage.ftp.host'),
      fieldProps: {
        placeholder: t('system.storage.ftp.host.placeholder'),
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
      title: t('system.storage.ftp.port'),
      fieldProps: {
        placeholder: t('system.storage.ftp.port.placeholder'),
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
      title: t('system.storage.ftp.username'),
      fieldProps: {
        placeholder: t('system.storage.ftp.username.placeholder'),
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
      title: t('system.storage.ftp.password'),
      fieldProps: {
        placeholder: t('system.storage.ftp.password.placeholder'),
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
      title: t('system.storage.ftp.root'),
      fieldProps: {
        placeholder: t('system.storage.ftp.root.placeholder'),
      },
      tooltip: t('system.storage.ftp.root.tooltip'),
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'timeout'],
      valueType: 'digit',
      title: t('system.storage.ftp.timeout'),
      fieldProps: {
        placeholder: t('system.storage.ftp.timeout.placeholder'),
        addonAfter: t('system.storage.ftp.timeout.suffix')
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
      title: t('system.storage.ftp.passive'),
      tooltip: t('system.storage.ftp.passive.tooltip'),
      colProps: { span: 8 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    {
      dataIndex: ['ftp', 'ssl'],
      valueType: 'switch',
      title: t('system.storage.ftp.ssl'),
      tooltip: t('system.storage.ftp.ssl.tooltip'),
      colProps: { span: 8 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'ftp'
      }
    },
    // SFTP 配置
    {
      fieldRender: () => <Divider>{t('system.storage.sftp.title')}</Divider>,
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
      title: t('system.storage.sftp.host'),
      fieldProps: {
        placeholder: t('system.storage.sftp.host.placeholder'),
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
      title: t('system.storage.sftp.port'),
      fieldProps: {
        placeholder: t('system.storage.sftp.port.placeholder'),
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
      title: t('system.storage.sftp.username'),
      fieldProps: {
        placeholder: t('system.storage.sftp.username.placeholder'),
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
      title: t('system.storage.sftp.password'),
      fieldProps: {
        placeholder: t('system.storage.sftp.password.placeholder'),
      },
      tooltip: t('system.storage.sftp.password.tooltip'),
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'root'],
      valueType: 'text',
      title: t('system.storage.sftp.root'),
      fieldProps: {
        placeholder: t('system.storage.sftp.root.placeholder'),
      },
      tooltip: t('system.storage.sftp.root.tooltip'),
      colProps: { span: 12 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'timeout'],
      valueType: 'digit',
      title: t('system.storage.sftp.timeout'),
      fieldProps: {
        placeholder: t('system.storage.sftp.timeout.placeholder'),
        addonAfter: t('system.storage.sftp.timeout.suffix')
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
      title: t('system.storage.sftp.private_key'),
      fieldProps: {
        placeholder: t('system.storage.sftp.private_key.placeholder'),
        rows: 4
      },
      tooltip: t('system.storage.sftp.private_key.tooltip'),
      colProps: { span: 24 },
      dependency: {
        dependencies: ['default'],
        visible: (values) => values.default === 'sftp'
      }
    },
    {
      dataIndex: ['sftp', 'passphrase'],
      valueType: 'password',
      title: t('system.storage.sftp.passphrase'),
      fieldProps: {
        placeholder: t('system.storage.sftp.passphrase.placeholder'),
      },
      tooltip: t('system.storage.sftp.passphrase.tooltip'),
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
    message.success(t('system.storage.save.success'));
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
      message.success(t('system.storage.test.success'));
    } finally {
      setTesting(false);
    }
  };

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>{t('system.storage.page.title')}</Title>
        <Text type="secondary">{t('system.storage.page.description')}</Text>
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
          <Card title={t('system.storage.test.title')}>
            <Form layout="vertical">
              <Form.Item label={t('system.storage.test.current_driver')}>
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
                {t('system.storage.test.button')}
              </Button>
            </Form>

            <Divider />

            <div style={{ fontSize: 12, color: '#666' }}>
              <Title level={5}><CloudUploadOutlined /> {t('system.storage.help.title')}</Title>
              <p><strong>{t('system.storage.help.local')}</strong>{t('system.storage.help.local.desc')}</p>
              <p><strong>{t('system.storage.help.s3')}</strong>{t('system.storage.help.s3.desc')}</p>
              <p><strong>{t('system.storage.help.ftp')}</strong>{t('system.storage.help.ftp.desc')}</p>
              <p><strong>{t('system.storage.help.sftp')}</strong>{t('system.storage.help.sftp.desc')}</p>
            </div>
          </Card>
        </Col>
      </Row>
    </>
  );
};

export default StorageConfig;
