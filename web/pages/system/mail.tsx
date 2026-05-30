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
    { label: t('system.mail.driver.smtp'), value: 'smtp' },
    { label: t('system.mail.driver.ses'), value: 'ses' },
    { label: t('system.mail.driver.mailgun'), value: 'mailgun' },
    { label: t('system.mail.driver.postmark'), value: 'postmark' },
    { label: t('system.mail.driver.resend'), value: 'resend' },
    { label: t('system.mail.driver.log'), value: 'log' },
    { label: t('system.mail.driver.array'), value: 'array' },
  ];

  const columns: FormColumn<MailResponse>[] = [
    {
      dataIndex: ['other', 'mode'],
      valueType: 'radio',
      title: t('system.mail.mode'),
      fieldProps: {
        options: [
          { label: t('system.mail.mode.single'), value: 'single'},
          { label: t('system.mail.mode.failover'), value: 'failover'},
          { label: t('system.mail.mode.roundrobin'), value: 'roundrobin'},
        ]
      },
      required: true,
      colProps: {span: 24}
    },
    {
      dataIndex: ['mail', 'default'],
      valueType: 'radioButton',
      title: t('system.mail.driver'),
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
      title: t('system.mail.driver'),
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
      title: t('system.mail.from_address'),
      fieldProps: {
        placeholder: t('system.mail.from_address.placeholder'),
      },
      required: true,
      colProps: {span: 12}
    },
    {
      dataIndex: ['mail', 'from', 'name'],
      valueType: 'text',
      title: t('system.mail.from_name'),
      fieldProps: {
        placeholder: t('system.mail.from_name.placeholder'),
      },
      required: true,
      colProps: {span: 12}
    },
    {
      fieldRender: () => <Divider>{t('system.mail.smtp.title')}</Divider>,
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
      title: t('system.mail.host'),
      fieldProps: {
        placeholder: t('system.mail.host.placeholder'),
      },
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
      title: t('system.mail.port'),
      fieldProps: {
        placeholder: t('system.mail.port.placeholder'),
      },
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
      title: t('system.mail.username'),
      fieldProps: {
        placeholder: t('system.mail.username.placeholder'),
      },
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
      title: t('system.mail.password'),
      fieldProps: {
        placeholder: t('system.mail.password.placeholder'),
      },
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
      fieldRender: () => <Divider>{t('system.mail.log.title')}</Divider>,
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
      title: t('system.mail.log.channel'),
      fieldProps: {
        placeholder: t('system.mail.log.channel.placeholder'),
      },
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
      fieldRender: () => <Divider>{t('system.mail.postmark.title')}</Divider>,
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
      title: t('system.mail.postmark.token'),
      fieldProps: {
        placeholder: t('system.mail.postmark.token.placeholder'),
      },
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
      fieldRender: () => <Divider>{t('system.mail.resend.title')}</Divider>,
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
      title: t('system.mail.resend.key'),
      fieldProps: {
        placeholder: t('system.mail.resend.key.placeholder'),
      },
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
      fieldRender: () => <Divider>{t('system.mail.mailgun.title')}</Divider>,
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
      title: t('system.mail.mailgun.domain'),
      fieldProps: {
        placeholder: t('system.mail.mailgun.domain.placeholder'),
      },
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
      title: t('system.mail.mailgun.secret'),
      fieldProps: {
        placeholder: t('system.mail.mailgun.secret.placeholder'),
      },
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
      title: t('system.mail.mailgun.endpoint'),
      fieldProps: {
        placeholder: t('system.mail.mailgun.endpoint.placeholder'),
      },
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
      fieldRender: () => <Divider>{t('system.mail.ses.title')}</Divider>,
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
      title: t('system.mail.ses.key'),
      fieldProps: {
        placeholder: t('system.mail.ses.key.placeholder'),
      },
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
      title: t('system.mail.ses.secret'),
      fieldProps: {
        placeholder: t('system.mail.ses.secret.placeholder'),
      },
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
      title: t('system.mail.ses.region'),
      fieldProps: {
        placeholder: t('system.mail.ses.region.placeholder'),
      },
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
      title: t('system.mail.ses.token'),
      fieldProps: {
        placeholder: t('system.mail.ses.token.placeholder'),
      },
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
    message.success(t('system.mail.save.success'));
  };

  const onTestFinish = async (values: any) => {
    try {
      setTesting(true);
      await sendTestMail(values.to);
      message.success(t('system.mail.test.success'));
    } finally {
      setTesting(false);
    }
  };

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>{t('system.mail.page.title')}</Title>
        <Text type="secondary">{t('system.mail.page.description')}</Text>
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
          <Card title={t('system.mail.test.title')}>
            <Form
              form={testForm}
              layout="vertical"
              onFinish={onTestFinish}
            >
              <Form.Item
                name="to"
                label={t('system.mail.test.to')}
                rules={[{ required: true, type: 'email' }]}
              >
                <Input placeholder={t('system.mail.test.to.placeholder')} />
              </Form.Item>

              <Button type="primary" htmlType="submit" block icon={<SendOutlined />} loading={testing}>
                {t('system.mail.test.send')}
              </Button>
            </Form>
          </Card>
        </Col>
      </Row>
    </>
  );
};

export default MailConfig;
