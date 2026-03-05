import React, {useEffect, useRef, useState} from 'react';
import {
  Button,
  Card,
  Form,
  Input,
  message,
  Row,
  Col,
  Typography,
  Divider
} from 'antd';
import { SendOutlined } from '@ant-design/icons';
import { useTranslation } from 'react-i18next';
import { getMailConfig, saveMailConfig, sendTestMail } from '@/api/system/sysMail.ts';
import type {FormColumn} from "@/components/XinFormField/FieldRender";
import XinForm, {type XinFormRef} from "@/components/XinForm";
const { Text, Title } = Typography;

interface MailResponse {
  other: {
    mode: 'single' | 'failover' | 'roundrobin',
    mailers: string[];
  },
  mail: {
    default: string;
    mailers: {
      smtp: {
        url: string;
        host: string;
        port: string;
        username: string;
        password: string;
      },
      log: {
        channel: string;
      }
    },
    from: {
      address: string;
      name: string;
    }
  },
  services: {
    postmark: {
      token: string;
    },
    ses: {
      key: string;
      secret: string;
      region: string;
      token: string;
    },
    resend: {
      key: string;
    },
    mailgun: {
      domain: string;
      secret: string;
      endpoint: string;
    }
  }
}

const MailConfig: React.FC = () => {
  const { t } = useTranslation();

  const formRef = useRef<XinFormRef>(null);
  const [form] = Form.useForm();
  const [testForm] = Form.useForm();
  const [testing, setTesting] = useState(false);

  const driverOptions = [
    { label: 'SMTP', value: 'smtp' },
    { label: 'SES (Amazon)', value: 'ses' },
    { label: 'Mailgun', value: 'mailgun' },
    { label: 'Postmark', value: 'postmark' },
    { label: 'Resend', value: 'resend' },
    { label: 'Log (日志)', value: 'log' },
    { label: 'Array (调试)', value: 'array' },
  ];

  const columns: FormColumn<MailResponse>[] = [
    {
      dataIndex: ['other', 'mode'],
      valueType: 'radio',
      title: '模式切换',
      fieldProps: {
        options: [
          { label: '单驱动', value: 'single'},
          { label: '故障切换', value: 'failover'},
          { label: '循环切换', value: 'roundrobin'},
        ]
      },
      required: true,
      colProps: {span: 24}
    },
    {
      dataIndex: ['mail', 'default'],
      valueType: 'radioButton',
      title: t('mail.driver'),
      fieldProps: {
        options: driverOptions
      },
      required: true,
      colProps: {span: 24},
      dependency: {
        dependencies: ['other'],
        visible: (values) => {
          return values.other?.mode === 'single'
        }
      }
    },
    {
      dataIndex: ['other', 'mailers'],
      valueType: 'checkbox',
      title: t('mail.driver'),
      fieldProps: { options:  driverOptions},
      colProps: {span: 24},
      dependency: {
        dependencies: ['other'],
        visible: (values) => {
          return values.other?.mode !== 'single'
        }
      }
    },
    {
      dataIndex: ['mail', 'from', 'address'],
      valueType: 'text',
      title: t('mail.from_address'),
      required: true,
      colProps: {span: 12}
    },
    {
      dataIndex: ['mail', 'from', 'name'],
      valueType: 'text',
      title: t('mail.from_name'),
      required: true,
      colProps: {span: 12}
    },
    {
      fieldRender: () => <Divider>SMTP 配置</Divider>,
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'smtp' || !!values.other?.mailers?.includes('smtp')
        }
      },
      colProps: {span: 24},
      noStyle: true
    },
    {
      dataIndex: ['mail', 'mailers', 'smtp', 'host'],
      valueType: 'text',
      title: t('mail.host'),
      required: true,
      colProps: {span: 16},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'smtp' || !!values.other?.mailers?.includes('smtp')
        }
      }
    },
    {
      dataIndex: ['mail', 'mailers', 'smtp', 'port'],
      valueType: 'digit',
      title: t('mail.port'),
      required: true,
      colProps: {span: 8},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'smtp' || !!values.other?.mailers?.includes('smtp')
        }
      }
    },
    {
      dataIndex: ['mail', 'mailers', 'smtp', 'username'],
      valueType: 'text',
      title: t('mail.username'),
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'smtp' || !!values.other?.mailers?.includes('smtp')
        }
      }
    },
    {
      dataIndex: ['mail', 'mailers', 'smtp', 'password'],
      valueType: 'password',
      title: t('mail.password'),
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'smtp' || !!values.other?.mailers?.includes('smtp')
        }
      }
    },
    {
      fieldRender: () => <Divider>日志配置</Divider>,
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'log' || !!values.other?.mailers?.includes('log')
        }
      },
      colProps: {span: 24},
      noStyle: true
    },
    {
      dataIndex: ['mail', 'mailers', 'log', 'channel'],
      valueType: 'text',
      title: '日志驱动',
      required: true,
      colProps: {span: 24},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'log' || !!values.other?.mailers?.includes('log')
        }
      }
    },
    {
      fieldRender: () => <Divider>Postmark 配置</Divider>,
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'postmark' || !!values.other?.mailers?.includes('postmark')
        }
      },
      colProps: {span: 24},
      noStyle: true
    },
    {
      dataIndex: ['services', 'postmark', 'token'],
      valueType: 'text',
      title: 'Postmark token',
      required: true,
      colProps: {span: 24},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'postmark' || !!values.other?.mailers?.includes('postmark')
        }
      }
    },
    {
      fieldRender: () => <Divider>Resend 配置</Divider>,
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'resend' || !!values.other?.mailers?.includes('resend')
        }
      },
      colProps: {span: 24},
      noStyle: true
    },
    {
      dataIndex: ['services', 'resend', 'key'],
      valueType: 'text',
      title: 'Resend KEY',
      required: true,
      colProps: {span: 24},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'resend' || !!values.other?.mailers?.includes('resend')
        }
      }
    },
    {
      fieldRender: () => <Divider>Mailgun 配置</Divider>,
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'mailgun' || !!values.other?.mailers?.includes('mailgun')
        }
      },
      colProps: {span: 24},
      noStyle: true
    },
    {
      dataIndex: ['services', 'mailgun', 'domain'],
      valueType: 'text',
      title: 'Mailgun Domain',
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'mailgun' || !!values.other?.mailers?.includes('mailgun')
        }
      }
    },
    {
      dataIndex: ['services', 'mailgun', 'secret'],
      valueType: 'text',
      title: 'Mailgun Secret',
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'mailgun' || !!values.other?.mailers?.includes('mailgun')
        }
      }
    },
    {
      dataIndex: ['services', 'mailgun', 'endpoint'],
      valueType: 'text',
      title: 'Mailgun Endpoint',
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'mailgun' || !!values.other?.mailers?.includes('mailgun')
        }
      }
    },
    {
      fieldRender: () => <Divider>SES (Amazon) 配置</Divider>,
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'ses' || !!values.other?.mailers?.includes('ses')
        }
      },
      colProps: {span: 24},
      noStyle: true
    },
    {
      dataIndex: ['services', 'ses', 'key'],
      valueType: 'text',
      title: 'SES KEY',
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'ses' || !!values.other?.mailers?.includes('ses')
        }
      }
    },
    {
      dataIndex: ['services', 'ses', 'secret'],
      valueType: 'text',
      title: 'SES Secret',
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'ses' || !!values.other?.mailers?.includes('ses')
        }
      }
    },
    {
      dataIndex: ['services', 'ses', 'region'],
      valueType: 'text',
      title: 'SES Region',
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'ses' || !!values.other?.mailers?.includes('ses')
        }
      }
    },
    {
      dataIndex: ['services', 'ses', 'token'],
      valueType: 'text',
      title: 'SES Token',
      required: true,
      colProps: {span: 12},
      dependency: {
        dependencies: ['mail', 'other'],
        visible: (values) => {
          return values.mail?.default === 'ses' || !!values.other?.mailers?.includes('ses')
        }
      }
    }
  ];

  useEffect(() => {
    loadConfig();
  }, []);

  const loadConfig = async () => {
    const { data } = await getMailConfig();
    if (data.success) {
      form.setFieldsValue(data.data);
    }
  };

  const onFinish = async (values: any) => {
    await saveMailConfig(values);
    message.success(t('mail.save.success'));
  };

  const onTestFinish = async (values: any) => {
    try {
      setTesting(true);
      await sendTestMail(values.to);
      message.success(t('mail.test.success'));
    } finally {
      setTesting(false);
    }
  };

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>邮件设置</Title>
        <Text type="secondary">邮件配置用于发送邮件，支持故障切换与循环切换</Text>
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
              rowProps={{
                gutter: [10, 0]
              }}
            />
          </Card>
        </Col>

        <Col span={8}>
          <Card title={t('mail.test.title')}>
            <Form
              form={testForm}
              layout="vertical"
              onFinish={onTestFinish}
            >
              <Form.Item
                name="to"
                label={t('mail.test.to')}
                rules={[{ required: true, type: 'email' }]}
              >
                <Input placeholder="receiver@example.com" />
              </Form.Item>

              <Button type="primary" htmlType="submit" block icon={<SendOutlined />} loading={testing}>
                {t('mail.test.send')}
              </Button>
            </Form>
          </Card>
        </Col>
      </Row>
    </>
  );
};

export default MailConfig;
