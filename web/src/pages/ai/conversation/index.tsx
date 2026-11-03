import XinTable from '@/components/XinTable';
import {Button, Drawer, Table, Tag, Tooltip, Typography} from 'antd';
import {EyeOutlined} from '@ant-design/icons';
import type {XinTableColumn} from '@/components/XinTable/typings.ts';
import type {IAgentConversation, IAgentMessage} from '@/domain/iAgents.ts';
import {getMessages} from '@/api/ai/conversation.ts';
import {useTranslation} from 'react-i18next';
import dayjs from 'dayjs';
import {useState} from 'react';

const {Title, Text} = Typography;

export default function AgentConversationPage() {
  const {t} = useTranslation();

  const [drawerOpen, setDrawerOpen] = useState(false);
  const [drawerTitle, setDrawerTitle] = useState('');
  const [messages, setMessages] = useState<IAgentMessage[]>([]);
  const [messagesLoading, setMessagesLoading] = useState(false);
  const [messagesTotal, setMessagesTotal] = useState(0);
  const [messagesPage, setMessagesPage] = useState(1);
  const [currentConversationId, setCurrentConversationId] = useState('');

  const fetchMessages = async (conversationId: string, page: number) => {
    setMessagesLoading(true);
    try {
      const res = await getMessages(conversationId, {page, pageSize: 20});
      const listData = res.data.data!;
      setMessages(listData.data);
      setMessagesTotal(listData.total);
    } finally {
      setMessagesLoading(false);
    }
  };

  const handleViewMessages = async (record: IAgentConversation) => {
    setCurrentConversationId(record.id!);
    setDrawerTitle(record.title || '');
    setMessagesPage(1);
    setDrawerOpen(true);
    await fetchMessages(record.id!, 1);
  };

  const handleMessagesPageChange = (page: number) => {
    setMessagesPage(page);
    fetchMessages(currentConversationId, page);
  };

  const columns: XinTableColumn<IAgentConversation>[] = [
    {
      title: t('ai.conversation.id'),
      dataIndex: 'id',
      hideInForm: true,
      width: 260,
      ellipsis: true,
      align: 'center',
    },
    {
      title: t('ai.conversation.username'),
      dataIndex: 'username',
      hideInForm: true,
      align: 'center',
      width: 120,
      render: (value: string) => value || t('ai.conversation.noUser'),
    },
    {
      title: t('ai.conversation.title'),
      dataIndex: 'title',
      valueType: 'text',
      ellipsis: true,
    },
    {
      title: t('ai.conversation.messageCount'),
      dataIndex: 'message_count',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      width: 100,
    },
    {
      title: t('ai.conversation.createdAt'),
      dataIndex: 'created_at',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      width: 180,
      render: (value: string) => value ? dayjs(value).format('YYYY-MM-DD HH:mm:ss') : '-',
    },
    {
      title: t('ai.conversation.updatedAt'),
      dataIndex: 'updated_at',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      width: 180,
      render: (value: string) => value ? dayjs(value).format('YYYY-MM-DD HH:mm:ss') : '-',
    },
  ];

  const messageColumns = [
    {
      title: t('ai.conversation.message.role'),
      dataIndex: 'role',
      width: 100,
      render: (role: string) => {
        const colorMap: Record<string, string> = {
          user: 'blue',
          assistant: 'green',
          system: 'orange',
        };
        return <Tag color={colorMap[role] || 'default'}>{t(`ai.conversation.message.role.${role}`, role)}</Tag>;
      },
    },
    {
      title: t('ai.conversation.message.agent'),
      dataIndex: 'agent',
      width: 150,
      ellipsis: true,
    },
    {
      title: t('ai.conversation.message.content'),
      dataIndex: 'content',
      ellipsis: true,
    },
    {
      title: t('ai.conversation.message.createdAt'),
      dataIndex: 'created_at',
      width: 180,
      render: (value: string) => value ? dayjs(value).format('YYYY-MM-DD HH:mm:ss') : '-',
    },
  ];

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>{t('ai.conversation.page.title')}</Title>
        <Text type="secondary">{t('ai.conversation.page.description')}</Text>
      </div>
      <XinTable<IAgentConversation>
        api={'/ai/conversation'}
        columns={columns}
        rowKey={'id'}
        accessName={'ai.conversation'}
        addShow={false}
        editShow={false}
        formProps={false}
        operateProps={{
          fixed: 'right',
          width: 120,
        }}
        operateRender={(record, dom) => [
          <Tooltip title={t('ai.conversation.viewMessages')} key="view">
            <Button
              type="primary"
              icon={<EyeOutlined/>}
              size="small"
              onClick={() => handleViewMessages(record)}
            />
          </Tooltip>,
          dom.del,
        ]}
        scroll={{x: 1100}}
        cardProps={{
          variant: 'borderless'
        }}
      />

      <Drawer
        title={`${t('ai.conversation.messageTitle')} - ${drawerTitle}`}
        open={drawerOpen}
        onClose={() => setDrawerOpen(false)}
        width={900}
      >
        <Table<IAgentMessage>
          dataSource={messages}
          columns={messageColumns}
          rowKey="id"
          loading={messagesLoading}
          pagination={{
            current: messagesPage,
            total: messagesTotal,
            pageSize: 20,
            onChange: handleMessagesPageChange,
            showSizeChanger: false,
          }}
          scroll={{x: 700}}
          size="small"
        />
      </Drawer>
    </>
  );
}
