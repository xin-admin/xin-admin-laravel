import {
  App,
  Empty,
  Space,
  Switch,
  Tag,
  Typography,
  theme, Spin, Avatar, Card, Tooltip,
} from 'antd';
import { useCallback, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { getAgentList, updateAgent } from '@/api/ai/agent.ts';
import type { IAgent } from '@/domain/iAgents.ts';

const { Title, Text, Paragraph } = Typography;

export default function AgentPage() {
  const { t } = useTranslation();
  const { token } = theme.useToken();
  const { message } = App.useApp();
  const [agents, setAgents] = useState<IAgent[]>([]);
  const [loading, setLoading] = useState(false);

  const loadAgents = useCallback(async () => {
    setLoading(true);
    try {
      const res = await getAgentList();
      setAgents(res.data.data ?? []);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    loadAgents();
  }, [loadAgents]);

  const handleToggleEnabled = async (id: number, enabled: boolean) => {
    try {
      await updateAgent(id, { enabled });
      setAgents((prev) =>
        prev.map((a) => (a.id === id ? { ...a, enabled } : a)),
      );
      message.success(t('sys.agent.update.success'));
    } catch {
      message.error(t('sys.agent.update.failed'));
    }
  };

  return (
    <>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: token.marginLG }}>
        <div>
          <Title level={3} style={{ marginBottom: token.marginXS }}>
            {t('sys.agent.page.title')}
          </Title>
          <Text type="secondary">{t('sys.agent.page.description')}</Text>
        </div>
      </div>

      <Spin spinning={loading}>
        {agents.length > 0 ? (
          <div className={'flex flex-wrap gap-2.5'}>
            {agents.map((agent) => (
              <Tooltip title={agent.description}>
                <Card hoverable variant={'borderless'} styles={{ body: { width: 236, padding: 10, overflow: "hidden" }}}>
                  <div className={'flex justify-between items-center mb-2.5'}>
                    <Space align={'center'}>
                      <Avatar src={agent.icon} size={32} />
                      <span style={{fontWeight: 700, fontSize: 18}}>{agent.name}</span>
                    </Space>
                    <Switch
                      checked={agent.enabled}
                      size="small"
                      onChange={(checked) =>
                        handleToggleEnabled(agent.id!, checked)
                      }
                    />
                  </div>
                  <Paragraph
                    type="secondary"
                    ellipsis={{ rows: 2 }}
                    style={{ marginBottom: token.marginSM }}
                  >
                    {agent.description}
                  </Paragraph>
                  <Space size={[4, 4]} wrap>
                    {agent.tags?.map((tag) => (
                      <Tag key={tag} color="blue">
                        {tag}
                      </Tag>
                    ))}
                  </Space>
                </Card>
              </Tooltip>

            ))}
          </div>
        ) : (
          <Empty description={t('sys.agent.empty')} />
        )}
      </Spin>
    </>
  );
}
