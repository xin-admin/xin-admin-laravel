import React, {useEffect, useRef, useState} from 'react';
import {
  Bubble, type BubbleListProps,
  Conversations,
  Sender,
  Welcome,
} from '@ant-design/x';
import type { BubbleItemType } from '@ant-design/x';
import {useXConversations, XRequest} from '@ant-design/x-sdk';
import {App, Avatar, Card, theme, type UploadFile} from 'antd';
import {
  DeleteOutlined,
  EditOutlined,
  UserOutlined,
} from '@ant-design/icons';
import { useTranslation } from 'react-i18next';
import { XMarkdown } from '@ant-design/x-markdown';
import '@ant-design/x-markdown/themes/light.css';
import { getConversations, getMessages, deleteConversation } from '@/api/ai/chat.ts';
import useGlobalStore from "@/stores/global";

/** 自定义气泡扩展字段（通过 extraInfo 传递） */
interface ChatExtraInfo {
  thinking?: string;
  isStreaming?: boolean;
  files?: UploadFile[];
}


const ChatPage: React.FC = () => {
  const { t } = useTranslation();
  const { message: appMessage, modal } = App.useApp();
  const { token } = theme.useToken();
  const themeConfig = useGlobalStore(state => state.themeConfig)

  const bubbleListRef = useRef<any>(null);

  const {
    conversations,
    setConversations,
    activeConversationKey,
    setActiveConversationKey,
  } = useXConversations({});

  // 消息列表
  const [messages, setMessages] = useState<BubbleItemType[]>([]);
  // 加载状态
  const [loading, setLoading] = useState(false);
  // 输入框内容
  const [senderValue, setSenderValue] = useState<string>("");
  // 输入框只读
  const [senderDisabled, setSenderDisabled] = useState<boolean>(false);

  // 加载会话列表
  const loadConversations = async () => {
    const {data} = await getConversations();
    if (data.success && data.data) {
      setConversations(data.data);
    }
  }

  // 加载消息列表
  const loadMessages = async (conversationId: string) => {
    const { data } = await getMessages(conversationId);
    if (data.success && data.data) {
      setMessages(data.data);
      setTimeout(() => {
        bubbleListRef.current?.scrollTo({ top: 'bottom' });
      }, 100);
    }
  }

  useEffect(() => { loadConversations(); }, []);

  // 会话操作
  const handleActiveChange = (key: string) => {
    setActiveConversationKey(key);
    loadMessages(key);
  }

  // 新建会话
  const handleNewChat = () => {
    setActiveConversationKey('');
    setMessages([]);
  };

  // 删除会话
  const handleDelete = (convId: string) => {
    modal.confirm({
      title: t('ai.chat.delete.confirm'),
      okButtonProps: { danger: true },
      onOk: async () => {
        await deleteConversation(convId);
        appMessage.success(t('ai.chat.delete.success'));
        if (activeConversationKey === convId) {
          setActiveConversationKey('');
          setMessages([]);
        }
        await loadConversations();
      },
    });
  }

  // 发送消息
  const handleSend = async (text: string) => {
    if (!text.trim() || loading) return;

    // 用户消息
    const userMessage: BubbleItemType = {
      key: `user-${Date.now()}`,
      role: 'user',
      content: text,
      status: 'success',
    };

    // AI 占位消息
    const aiMessage: BubbleItemType = {
      key: `assistant-${Date.now()}`,
      role: 'assistant',
      content: '',
      loading: true,
      extraInfo: { isStreaming: true } as ChatExtraInfo,
    };

    setMessages((prev) => [...prev, userMessage, aiMessage]);
    setLoading(true);
    setSenderDisabled(true);

    const token = localStorage.getItem('token');
    const baseUrl = import.meta.env.VITE_BASE_URL || '';
    let aiContent = '';
    XRequest(`${baseUrl}/ai/chat/send`, {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'text/event-stream',
      },
      method: "post",
      params: {
        conversation_id: activeConversationKey,
        message: text
      },
      callbacks: {
        onSuccess: async () => {
          setLoading(false);
          setSenderValue("");
          setSenderDisabled(false);
          if (!activeConversationKey) {
            const {data} = await getConversations();
            if (data.success && data.data && data.data.length > 0) {
              setConversations(data.data);
              const latest = data.data[0].key;
              setActiveConversationKey(latest as string);
            }
          }
        },
        onError: () => {
          setLoading(false);
          setSenderDisabled(false);
        },
        onUpdate: (msg) => {
          console.log("onUpdate", msg);
          const payload = msg.data;
          if (payload === '[DONE]') return;
          try {
            const event = JSON.parse(payload);
            // 正文内容增量
            if (event.type === 'text_delta' && event.delta) {
              aiContent += event.delta;
              setMessages((prev) => prev.map(m => (
                m.key === aiMessage.key ? {
                  ...m,
                  content: aiContent,
                  loading: false
                } : m
              )));
            }
            // 内容结束
            if (event.type === 'text_end') {
              setMessages((prev) => prev.map(m => (
                m.key === aiMessage.key ? {
                  ...m,
                  key: event.message_id,
                  loading: false
                } : m
              )));
            }
          } catch (err) {
            /* empty */
          }
        },
      }
    })
  }

  // ==================== 角色配置 ====================
  const roleConfig: BubbleListProps['role'] = {
    assistant: {
      placement: 'start',
      variant: 'filled',
      avatar: <Avatar src={'https://file.xinadmin.cn/file/favicons.ico'} />,
      // AI 消息内容：Markdown 渲染
      contentRender: (content: string, info: any) => {
        if (!content) return null;
        const isStreaming = info?.extraInfo?.isStreaming === true;
        return (
          <XMarkdown
            content={content}
            streaming={{
              hasNextChunk: isStreaming,
              enableAnimation: true,
              tail: isStreaming,
            }}
            openLinksInNewTab
            escapeRawHtml
          />
        );
      },
    },
    user: {
      placement: 'end',
      variant: 'filled',
      avatar: (
        <Avatar
          icon={<UserOutlined />}
          style={{ background: token.colorFillSecondary, color: token.colorTextSecondary }}
          size={36}
        />
      ),
    },
  };

  const hasMessages = messages.length > 0;

  return (
    <Card
      styles={{ body: { padding: 0, overflow: 'hidden', display: 'flex', height: 'calc(100vh - 180px)' } }}
      title={"Chat"}
    >
      {/* 左侧会话列表 */}
      <div
        style={{
          width: 280,
          borderRight: `1px solid ${token.colorBorderSecondary}`
        }}
      >
        <Conversations
          items={conversations}
          activeKey={activeConversationKey}
          onActiveChange={handleActiveChange}
          menu={(item) => ({
            items: [
              {
                label: 'Rename',
                key: 'Rename',
                icon: <EditOutlined />,
              },
              {
                type: 'divider',
              },
              {
                label: 'Delete Chat',
                key: 'delete',
                icon: <DeleteOutlined />,
                danger: true,
                onClick: () => handleDelete(item.key as string),
              },
            ],
          })}
          creation={{
            onClick: handleNewChat,
          }}
          style={{ flex: 1, overflow: 'auto' }}
        />
      </div>

      {/* 右侧对话区域 */}
      <div className={"flex-1 flex flex-col min-w-0 px-1"}>
        {/* 空状态 - 欢迎页 */}
        {!hasMessages && (
          <div className={"h-full flex items-center justify-center"}>
            <Welcome
              icon="https://file.xinadmin.cn/file/favicons.ico"
              style={{
                backgroundImage: themeConfig.themeScheme === 'dark' ?
                  'linear-gradient(97deg, rgba(90,196,255,0.12) 0%, rgba(174,136,255,0.12) 100%)'
                  : 'linear-gradient(97deg, #f2f9fe 0%, #f7f3ff 100%)',
                borderRadius: 20,
                width: '80%',
                padding: '20px'
              }}
              title={t('ai.chat.welcome.title')}
              description={t('ai.chat.welcome.description')}
              variant="borderless"
            />
          </div>
        )}

        {/* 消息列表 */}
        {hasMessages && (
          <div style={{ flex: 1, overflow: 'hidden' }}>
            <Bubble.List
              ref={bubbleListRef}
              items={messages}
              autoScroll
              style={{ height: '100%', padding: 16 }}
              role={roleConfig}
            />
          </div>
        )}

        {/* 发送区域 */}
        <div style={{ padding: '0 16px 16px' }}>
          <Sender
            loading={loading}
            disabled={senderDisabled}
            value={senderValue}
            onChange={setSenderValue}
            placeholder={t('ai.chat.placeholder')}
            onSubmit={handleSend}
            onCancel={() => setLoading(false)}
            autoSize={{minRows: 1, maxRows: 6}}
          />
        </div>
      </div>
    </Card>
  );
};

export default ChatPage;
