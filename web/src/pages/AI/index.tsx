import {
  Attachments,
  Bubble,
  Conversations,
  Prompts,
  Sender,
  Welcome,
  XStream,
  type ConversationsProps
} from '@ant-design/x';
import { createStyles } from 'antd-style';
import React, { useEffect } from 'react';
import {addApi,listApi,getApi} from '@/services/common/table';
import {
  CloudUploadOutlined,
  CommentOutlined,
  EllipsisOutlined,
  FireOutlined,
  HeartOutlined,
  PaperClipOutlined,
  PlusOutlined,
  ReadOutlined,
  ShareAltOutlined,
  SmileOutlined, UserOutlined,
} from '@ant-design/icons';
import { Avatar, Badge, Button, type GetProp, Space } from 'antd';
import { useModel } from '@umijs/max';
import { FormattedMessage } from '@@/exports';
import { IAiConversationGroup } from '@/domain/iAiConversationGroup';
import { IAiConversation } from '@/domain/iAiConversation';

const useStyle = createStyles(({ token, css }) => {
  return {
    layout: css`
        width: 100%;
        min-width: 1000px;
        height: 722px;
        border-radius: ${token.borderRadius}px;
        border: 1px solid ${token.colorBorder};
        display: flex;
        background: ${token.colorBgContainer};
        font-family: AlibabaPuHuiTi, ${token.fontFamily}, sans-serif;
    `,
    menu: css`
        background: ${token.colorBgLayout}80;
        width: 280px;
        height: 100%;
        padding-top: 20px;
        display: flex;
        flex-direction: column;
    `,
    conversations: css`
        padding: 0 12px;
        flex: 1;
        overflow-y: auto;
    `,
    chat: css`
        height: 100%;
        width: 100%;
        margin: 0 auto;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        padding: ${token.paddingLG}px;
        gap: 16px;
    `,
    messages: css`
        flex: 1;
        padding-right: 20px;
    `,
    placeholder: css`
        padding-top: 32px;
    `,
    sender: css`
        box-shadow: ${token.boxShadow};
    `,
    logo: css`
        display: flex;
        height: 72px;
        align-items: center;
        justify-content: start;
        padding: 0 24px;
        box-sizing: border-box;

        img {
            width: 24px;
            height: 24px;
            display: inline-block;
        }

        span {
            display: inline-block;
            margin: 0 8px;
            font-weight: bold;
            color: ${token.colorText};
            font-size: 16px;
        }
    `,
    addBtn: css`
        background: #1677ff0f;
        border: 1px solid #1677ff34;
        width: calc(100% - 24px);
        margin: 0 12px 24px 12px;
    `,
  };
});

const placeholderPromptsItems: GetProp<typeof Prompts, 'items'> = [
  {
    key: '1',
    label: (
      <Space align="start">
        <FireOutlined style={{ color: '#FF4D4F' }} />
        <FormattedMessage id="ai.hot_topics" />
      </Space>
    ),
    description: <FormattedMessage id="ai.hot_topics.desc" />,
    children: [
      {
        key: '1-1',
        description: `什么是XinAdmin？`,
      },
      {
        key: '1-2',
        description: `请简单说一下秦始皇！`,
      },
      {
        key: '1-3',
        description: `从郑州到北京的距离是多远？`,
      },
    ],
  },
  {
    key: '2',
    label: (
      <Space align="start">
        <ReadOutlined style={{ color: '#1890FF' }} />
        <FormattedMessage id="ai.design_guide" />
      </Space>
    ),
    description: <FormattedMessage id="ai.design_guide.desc" />,
    children: [
      {
        key: '2-1',
        icon: <HeartOutlined />,
        description: `Know the well`,
      },
      {
        key: '2-2',
        icon: <SmileOutlined />,
        description: `Set the AI role`,
      },
      {
        key: '2-3',
        icon: <CommentOutlined />,
        description: `Express the feeling`,
      },
    ],
  },
];

const senderPromptsItems: GetProp<typeof Prompts, 'items'> = [
  {
    key: '1',
    description: <FormattedMessage id="ai.hot_topics" />,
    icon: <FireOutlined style={{ color: '#FF4D4F' }} />,
  },
  {
    key: '2',
    description: <FormattedMessage id="ai.design_guide" />,
    icon: <ReadOutlined style={{ color: '#1890FF' }} />,
  },
];

interface IAIMessage {
  key?: string | number;
  role?: string;
  content?: string;
  loading?: boolean;
}

const Independent: React.FC = () => {
  const userInfo = useModel('userModel', ({ userInfo }) => userInfo);
  const roles: GetProp<typeof Bubble.List, 'roles'> = {
    ai: {
      placement: 'start',
      avatar: { icon: <UserOutlined />, style: { color: '#f56a00', backgroundColor: '#fde3cf' } },
    },
    local: {
      avatar: { icon: <Avatar src={userInfo?.avatar_url} style={{ marginRight: '10px' }} /> },
      placement: 'end',
      header: userInfo?.nickname,
    },
  };

  const { styles } = useStyle();

  // ==================== State ====================
  const [loading, setLoading] = React.useState(false);

  const [headerOpen, setHeaderOpen] = React.useState(false);

  const [content, setContent] = React.useState('');

  const [conversationsItems, setConversationsItems] = React.useState<ConversationsProps['items']>();

  const [activeKeys, setActiveKey] = React.useState<string>();

  const [attachedFiles, setAttachedFiles] = React.useState<GetProp<typeof Attachments, 'items'>>([]);

  useEffect(() => {
    if(userInfo) {
      listApi('/ai').then((res) => {
        setConversationsItems(res.data.map((item: IAiConversationGroup) => ({
          key: item.uuid,
          label: item.name,
        })))
      })
    }
  },[])

  // ==================== Runtime ====================
  const [messages, setMessages] = React.useState<IAIMessage[]>([]);

  // ==================== Event ====================
  const onSubmit = async (nextContent: string) => {
    if (!nextContent) return;
    let uuid;
    if(!activeKeys) {
      // 创建新会话
      let addGroupRes = await addApi('/ai', { name: nextContent.slice(0, 20), })
      setActiveKey(addGroupRes.data.uuid);
      uuid = addGroupRes.data.uuid;
    }else {
      uuid = activeKeys;
    }
    let token = localStorage.getItem('x-token') ? localStorage.getItem('x-token')! : '';
    let key = Date.now();
    setLoading(true);
    let startMessage = [...messages];
    let thisMessage = [
      { key: key - 1, role: 'local', content: nextContent, loading: false },
      { key: key, role: 'ai', content: '', loading: true }
    ]
    setMessages([...startMessage, ...thisMessage])
    setContent('');
    const response = await fetch(process.env.DOMAIN + '/ai/send', {
      'headers': {
        'accept': '*/*',
        'content-type': 'text/plain;charset=UTF-8',
        'x-token': token,
      },
      'body': JSON.stringify({ message: nextContent, uuid }),
      'method': 'POST',
    });
    if (!response.body) return;
    let currentContent = '';
    for await (const chunk of XStream({
      readableStream: response.body,
    })) {
      if (!chunk || !chunk.data || typeof chunk.data !== 'string') continue;
      let currentMessage = chunk.data.replace(/\s*/g, '');
      if (currentMessage == 'end') {
        setLoading(false);
      } else {
        try{
          let data = JSON.parse(currentMessage);
          if (data && data.delta && data.delta.content) {
            currentContent = currentContent + data.delta.content
            thisMessage[1].content = currentContent;
            thisMessage[1].loading = false;
            setMessages([...messages, ...thisMessage]);
          }
        }catch (e){}
      }
    }
  };

  const onPromptsItemClick: GetProp<typeof Prompts, 'onItemClick'> = (info) => {
    console.log('onPromptsItemClick', info);
  };

  const onAddConversation = () => {
    console.log('onAddConversation');
    setActiveKey(undefined);
    setMessages([]);
  };

  const onConversationClick: GetProp<typeof Conversations, 'onActiveChange'> = (key) => {
    console.log('onConversationClick');
    setActiveKey(key);
    getApi('/ai', key).then((res) => {
      setMessages(res.data.map((item: IAiConversation) => {
        if (item.role === 'system') return;
        return {
          role: item.role === 'assistant' ? 'ai' : 'local',
          content: item.message,
          key: item.id,
          loading: false
        }
      }))
    })
  };

  const handleFileChange: GetProp<typeof Attachments, 'onChange'> = (info) => setAttachedFiles(info.fileList);

  // ==================== Nodes ====================
  const placeholderNode = (
    <Space direction="vertical" size={16} className={styles.placeholder}>
      <Welcome
        variant="borderless"
        icon="https://mdn.alipayobjects.com/huamei_iwk9zp/afts/img/A*s5sNRo5LjfQAAAAAAAAAAAAADgCCAQ/fmt.webp"
        title={<FormattedMessage id={'ai.hello'} />}
        description={<FormattedMessage id={'ai.desc'} />}
        extra={
          <Space>
            <Button icon={<ShareAltOutlined />} />
            <Button icon={<EllipsisOutlined />} />
          </Space>
        }
      />
      <Prompts
        title={<FormattedMessage id={'ai.want'} />}
        items={placeholderPromptsItems}
        styles={{ list: { width: '100%' }, item: { flex: 1 } }}
        onItemClick={onPromptsItemClick}
      />
    </Space>
  );

  const attachmentsNode = (
    <Badge dot={attachedFiles.length > 0 && !headerOpen}>
      <Button type="text" icon={<PaperClipOutlined />} onClick={() => setHeaderOpen(!headerOpen)} />
    </Badge>
  );

  const senderHeader = (
    <Sender.Header title="上传附件" open={headerOpen} onOpenChange={setHeaderOpen}>
      <Attachments
        beforeUpload={() => false}
        items={attachedFiles}
        onChange={handleFileChange}
        placeholder={(type) =>
          type === 'drop'
            ? { title: 'Drop file here' }
            : {
              icon: <CloudUploadOutlined />,
              title: 'Upload files',
              description: 'Click or drag files to this area to upload',
            }
        }
      />
    </Sender.Header>
  );

  // ==================== Render =================
  return (
    <div className={styles.layout}>
      <div className={styles.menu}>
        {/* 🌟 添加会话 */}
        <Button
          onClick={onAddConversation}
          type="link"
          className={styles.addBtn}
          icon={<PlusOutlined />}
        >
          <FormattedMessage id={'ai.new_conversation'} />
        </Button>
        {/* 🌟 会话管理 */}
        <Conversations
          items={conversationsItems}
          className={styles.conversations}
          activeKey={activeKeys}
          onActiveChange={onConversationClick}
        />
      </div>
      <div className={styles.chat}>
        {/* 🌟 消息列表 */}
        <Bubble.List
          items={messages.length > 0 ? messages : [{ content: placeholderNode, variant: 'borderless' }]}
          roles={roles}
          className={styles.messages}
        />
        {/* 🌟 提示词 */}
        <Prompts items={senderPromptsItems} onItemClick={onPromptsItemClick} />
        {/* 🌟 输入框 */}
        <Sender
          value={content}
          header={senderHeader}
          onSubmit={onSubmit}
          onChange={setContent}
          prefix={attachmentsNode}
          loading={loading}
          className={styles.sender}
        />
      </div>
    </div>
  );
};

export default Independent;
