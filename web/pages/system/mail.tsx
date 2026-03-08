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
    { label: t('mail.driver.smtp'), value: 'smtp' },
    { label: t('mail.driver.ses'), value: 'ses' },
    { label: t('mail.driver.mailgun'), value: 'mailgun' },
    { label: t('mail.driver.postmark'), value: 'postmark' },
    { label: t('mail.driver.resend'), value: 'resend' },
    { label: t('mail.driver.log'), value: 'log' },
    { label: t('mail.driver.array'), value: 'array' },
  ];

  const columns: FormColumn<MailResponse>[] = [
    {
      dataIndex: ['other', 'mode'],
      valueType: 'radio',
      title: t('mail.mode'),
      fieldProps: {
        options: [
          { label: t('mail.mode.single'), value: 'single'},
          { label: t('mail.mode.failover'), value: 'failover'},
          { label: t('mail.mode.roundrobin'), value: 'roundrobin'},
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
      fieldProps: {
        placeholder: t('mail.from_address.placeholder'),
      },
      required: true,
      colProps: {span: 12}
    },
    {
      dataIndex: ['mail', 'from', 'name'],
      valueType: 'text',
      title: t('mail.from_name'),
      fieldProps: {
        placeholder: t('mail.from_name.placeholder'),
      },
      required: true,
      colProps: {span: 12}
    },
    {
      fieldRender: () => <Divider>{t('mail.smtp.title')}</Divider>,
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
      fieldProps: {
        placeholder: t('mail.host.placeholder'),
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
      title: t('mail.port'),
      fieldProps: {
        placeholder: t('mail.port.placeholder'),
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
      title: t('mail.username'),
      fieldProps: {
        placeholder: t('mail.username.placeholder'),
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
      title: t('mail.password'),
      fieldProps: {
        placeholder: t('mail.password.placeholder'),
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
      fieldRender: () => <Divider>{t('mail.log.title')}</Divider>,
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
      title: t('mail.log.channel'),
      fieldProps: {
        placeholder: t('mail.log.channel.placeholder'),
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
      fieldRender: () => <Divider>{t('mail.postmark.title')}</Divider>,
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
      title: t('mail.postmark.token'),
      fieldProps: {
        placeholder: t('mail.postmark.token.placeholder'),
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
      fieldRender: () => <Divider>{t('mail.resend.title')}</Divider>,
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
      title: t('mail.resend.key'),
      fieldProps: {
        placeholder: t('mail.resend.key.placeholder'),
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
      fieldRender: () => <Divider>{t('mail.mailgun.title')}</Divider>,
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
      title: t('mail.mailgun.domain'),
      fieldProps: {
        placeholder: t('mail.mailgun.domain.placeholder'),
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
      title: t('mail.mailgun.secret'),
      fieldProps: {
        placeholder: t('mail.mailgun.secret.placeholder'),
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
      title: t('mail.mailgun.endpoint'),
      fieldProps: {
        placeholder: t('mail.mailgun.endpoint.placeholder'),
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
      fieldRender: () => <Divider>{t('mail.ses.title')}</Divider>,
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
      title: t('mail.ses.key'),
      fieldProps: {
        placeholder: t('mail.ses.key.placeholder'),
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
      title: t('mail.ses.secret'),
      fieldProps: {
        placeholder: t('mail.ses.secret.placeholder'),
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
      title: t('mail.ses.region'),
      fieldProps: {
        placeholder: t('mail.ses.region.placeholder'),
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
      title: t('mail.ses.token'),
      fieldProps: {
        placeholder: t('mail.ses.token.placeholder'),
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
        <Title level={3}>{t('mail.page.title')}</Title>
        <Text type="secondary">{t('mail.page.description')}</Text>
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
                <Input placeholder={t('mail.test.to.placeholder')} />
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
