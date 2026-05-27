import React, { useEffect, useRef, useState } from 'react';
import {
  Alert,
  Button,
  Card,
  Form,
  message,
  Row,
  Col,
  Spin,
  Typography,
} from 'antd';
import { ThunderboltOutlined } from '@ant-design/icons';
import { useTranslation } from 'react-i18next';
import {getAiList, getAiConfig, saveAiConfig} from '@/api/system/sysAi.ts';
import type {AiLab, AiList} from '@/domain/iAi.ts';
import type { FormColumn } from '@/components/XinFormField/FieldRender';
import XinForm, { type XinFormRef } from '@/components/XinForm';

const { Text, Title } = Typography;

interface AiResponse {
  default: string;
  providers: Record<string, Record<string, any>>;
}

const AiConfig: React.FC = () => {
  const { t } = useTranslation();
  const formRef = useRef<XinFormRef>(null);
  const [form] = Form.useForm();
  const [aiList, setAiList] = useState<AiList>({
    default: []
  });
  const [testing, setTesting] = useState(false);
  const [testResult, setTestResult] = useState<string | null>(null);
  const [testError, setTestError] = useState<string | null>(null);

  const dependency = (lab: AiLab): FormColumn<AiResponse>['dependency'] => ({
    dependencies: ['default'],
    visible: (values) => values.default === lab
  })

  const apikeyColumn = (lab: AiLab): FormColumn<AiResponse> => ({
    dataIndex: ['providers', lab, 'key'],
    valueType: 'password',
    title: t('ai.provider.api_key', { lab: lab }),
    fieldProps: { placeholder: 'sk-...' },
    colProps: { span: 16 },
    dependency: dependency(lab)
  })

  const urlColumn = (lab: AiLab, url: string = ''): FormColumn<AiResponse> => ({
    dataIndex: ['providers', lab, 'url'],
    valueType: 'text',
    title: t('ai.provider.url', { lab: lab }),
    fieldProps: { placeholder: t('ai.provider.url.placeholder', {url: url}) },
    colProps: { span: 8 },
    dependency: dependency(lab)
  })

  const anthropicColumns: FormColumn<AiResponse>[] = [
    apikeyColumn('anthropic'),
    urlColumn('anthropic', 'https://api.anthropic.com/v1')
  ];

  const openaiColumns: FormColumn<AiResponse>[] = [
    apikeyColumn('openai'),
    urlColumn('openai', 'https://api.openai.com/v1'),
  ];

  const geminiColumns: FormColumn<AiResponse>[] = [
    apikeyColumn('gemini'),
    urlColumn('gemini', 'https://generativelanguage.googleapis.com/v1beta/')
  ];

  const ollamaColumns: FormColumn<AiResponse>[] = [
    apikeyColumn('ollama'),
    urlColumn('ollama', 'http://localhost:11434')
  ];

  const azureColumns: FormColumn<AiResponse>[] = [
    apikeyColumn('azure'),
    urlColumn('azure', 'https://<resource>.openai.azure.com'),
    {
      dataIndex: ['providers', 'azure', 'api_version'],
      valueType: 'text',
      title: t('ai.azure.api_version'),
      fieldProps: { placeholder: '2025-04-01-preview' },
      colProps: { span: 8 },
      dependency: dependency('azure')
    },
    {
      dataIndex: ['providers', 'azure', 'deployment'],
      valueType: 'text',
      title: t('ai.azure.deployment'),
      fieldProps: { placeholder: 'gpt-4o' },
      colProps: { span: 8 },
      dependency: dependency('azure')
    },
    {
      dataIndex: ['providers', 'azure', 'embedding_deployment'],
      valueType: 'text',
      title: t('ai.azure.embedding_deployment'),
      fieldProps: { placeholder: 'text-embedding-3-small' },
      colProps: { span: 8 },
      dependency: dependency('azure')
    },
    {
      dataIndex: ['providers', 'azure', 'image_deployment'],
      valueType: 'text',
      title: t('ai.azure.image_deployment'),
      fieldProps: { placeholder: 'gpt-image-1' },
      colProps: { span: 12 },
      dependency: dependency('azure')
    },
  ]

  const bedrockColumns: FormColumn<AiResponse>[] = [
    {
      dataIndex: ['providers', 'bedrock', 'region'],
      valueType: 'text',
      title: t('ai.bedrock.region'),
      fieldProps: { placeholder: 'us-east-1' },
      colProps: { span: 12 },
      dependency: dependency('bedrock')
    },
    apikeyColumn('bedrock'),
    {
      dataIndex: ['providers', 'bedrock', 'access_key_id'],
      valueType: 'password',
      title: t('ai.bedrock.access_key_id'),
      fieldProps: { placeholder: t('ai.provider.api_key.placeholder') },
      colProps: { span: 12 },
      dependency: dependency('bedrock')
    },
    {
      dataIndex: ['providers', 'bedrock', 'secret_access_key'],
      valueType: 'password',
      title: t('ai.bedrock.secret_access_key'),
      fieldProps: { placeholder: t('ai.provider.api_key.placeholder') },
      colProps: { span: 12 },
      dependency: dependency('bedrock')
    },
    {
      dataIndex: ['providers', 'bedrock', 'session_token'],
      valueType: 'password',
      title: t('ai.bedrock.session_token'),
      fieldProps: { placeholder: t('ai.provider.api_key.placeholder') },
      colProps: { span: 24 },
      dependency: dependency('bedrock')
    },
  ]

  const columns: FormColumn<AiResponse>[] = [
    {
      dataIndex: 'default',
      valueType: 'radio',
      title: t('ai.default'),
      tooltip: t('ai.default.tooltip'),
      colProps: { span: 24 },
      fieldProps: {
        options: aiList.default.map(item => ({
          label: item,
          value: item
        }))
      }
    },
    ...anthropicColumns,
    ...openaiColumns,
    ...geminiColumns,
    ...azureColumns,
    ...bedrockColumns,
    ...ollamaColumns,
    apikeyColumn('cohere'),
    apikeyColumn('deepseek'),
    apikeyColumn('eleven'),
    apikeyColumn('groq'),
    apikeyColumn('jina'),
    apikeyColumn('mistral'),
    apikeyColumn('openrouter'),
    apikeyColumn('voyageai'),
    apikeyColumn('xai'),
  ];

  useEffect(() => {
    loadConfig();
    getAiList().then(res => (
      res.data.data && setAiList(res.data.data)
    ))
  }, []);

  const loadConfig = async () => {
    const { data } = await getAiConfig();
    if (data.success) {
      form.setFieldsValue(data.data);
    }
  };

  const onFinish = async (values: any) => {
    await saveAiConfig(values);
    message.success(t('ai.save.success'));
  };

  const handleTestConnection = async () => {
    setTesting(true);
    setTestResult(null);
    setTestError(null);

    try {
      const token = localStorage.getItem('token');
      const baseUrl = import.meta.env.VITE_BASE_URL || '';
      const response = await fetch(`${baseUrl}/system/ai/test`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'text/event-stream',
        },
      });

      if (!response.ok) {
        const errorData = await response.json().catch(() => null);
        throw new Error(errorData?.msg || `HTTP ${response.status}`);
      }

      const reader = response.body?.getReader();
      if (!reader) {
        throw new Error('Stream not supported');
      }

      const decoder = new TextDecoder();
      let buffer = '';
      let resultText = '';

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;

        buffer += decoder.decode(value, { stream: true });
        const lines = buffer.split('\n');
        // Keep the last (potentially incomplete) line in buffer
        buffer = lines.pop() || '';

        for (const line of lines) {
          if (!line.startsWith('data: ')) continue;
          const payload = line.slice(6).trim();
          if (payload === '[DONE]') continue;

          try {
            const event = JSON.parse(payload);
            if (event.type === 'text_delta' && event.delta) {
              resultText += event.delta;
              setTestResult(resultText);
            }
          } catch {
            // Skip unparseable lines
          }
        }
      }

      message.success(t('ai.test.success'));
    } catch (err: any) {
      const msg = err?.message || 'Connection test failed';
      setTestError(msg);
      message.error(msg);
    } finally {
      setTesting(false);
    }
  };

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>{t('ai.page.title')}</Title>
        <Text type="secondary">{t('ai.page.description')}</Text>
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
                gutter: [10, 0],
              }}
            />
          </Card>
        </Col>
        <Col span={8}>
          <Card title={t('ai.test.title')}>
            <div className="flex flex-col gap-4">
              <Text type="secondary">{t('ai.test.hint')}</Text>

              <Button
                type="primary"
                block
                icon={<ThunderboltOutlined />}
                loading={testing}
                onClick={handleTestConnection}
              >
                {testing ? t('ai.test.testing') : t('ai.test.button')}
              </Button>

              {testing && !testResult && (
                <div className="flex justify-center py-8">
                  <Spin description={t('ai.test.testing')} />
                </div>
              )}

              {testResult && (
                <Alert
                  type="success"
                  title={t('ai.test.success')}
                  description={testResult}
                  showIcon
                />
              )}

              {testError && (
                <Alert
                  type="error"
                  title={t('ai.test.error')}
                  description={testError}
                  showIcon
                />
              )}
            </div>
          </Card>
        </Col>
      </Row>
    </>
  );
};

export default AiConfig;
