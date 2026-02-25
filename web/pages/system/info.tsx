import { Card, Divider, Tag, Timeline, Avatar, Typography } from 'antd';
import {
  InfoCircleOutlined,
  LinkOutlined,
  HistoryOutlined,
  UserOutlined,
  FileTextOutlined,
  GithubOutlined,
  QqOutlined
} from '@ant-design/icons';
import { useTranslation } from 'react-i18next';

const { Title } = Typography;

const SystemInfoPage = () => {
  const { t } = useTranslation();

  // 系统基本信息
  const systemInfo = [
    { label: t('system.info.label.name'), value: 'XinAdmin' },
    { label: t('system.info.label.version'), value: 'v2.0' },
    { label: t('system.info.label.build'), value: 'Vite 7.1.5' },
    { label: t('system.info.label.frontend'), value: 'React 19.1.0' },
    { label: t('system.info.label.ui'), value: 'Ant Design 5.27.1' },
    { label: t('system.info.label.css'), value: 'TailwindCSS 4.1.11' },
    { label: t('system.info.label.router'), value: 'React Router 7.8.2' },
    { label: t('system.info.label.state'), value: 'Zustand 5.0.8' },
    { label: t('system.info.label.ts'), value: '5.8.3' },
  ];

  // 项目地址
  const projectLinks = [
    { name: t('system.info.link.github'), url: 'https://github.com/xin-admin/xin-admin', icon: <GithubOutlined /> },
    { name: t('system.info.link.docs'), url: 'https://xinadmin.cn/ui/intro', icon: <FileTextOutlined /> },
    { name: t('system.info.link.demo'), url: 'https://ui.xinadmin.cn', icon: <LinkOutlined /> },
    { name: t('system.info.link.issues'), url: 'https://github.com/xin-admin/xin-admin/issues', icon: <InfoCircleOutlined /> },
  ];

  // 更新日志
  const changelog = [
    { time: '2025-11', version: 'v2.0', content: t('system.info.log.content') },
  ];

  // 作者信息
  const authorInfo = {
    name: t('system.info.author.name'),
    avatar: 'https://file.xinadmin.cn/file/favicons.ico',
    role: t('system.info.author.role'),
    contact: [
      { type: t('system.info.contact.github'), value: 'https://github.com/xin-admin/xin-admin', icon: <GithubOutlined /> },
      { type: t('system.info.contact.group'), value: 'Xin Admin Official Community', icon: <QqOutlined /> },
      { type: t('system.info.contact.discuss'), value: 'https://github.com/xin-admin/xin-admin/discussions', icon: <LinkOutlined /> },
    ],
  };

  return (
    <div className="min-h-screen">
      <div className={'mb-5'}>
        <Title level={3}>{t('system.info.title')}</Title>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {/* 系统基本信息卡片 */}
        <Card
          variant={'borderless'}
          title={
            <div className="flex items-center">
              <InfoCircleOutlined className="mr-2 text-blue-500" />
              <span>{t('system.info.basic.title')}</span>
            </div>
          }
        >
          <div className="space-y-2">
            {systemInfo.map((item, index) => (
              <div key={index} className="flex justify-between items-center py-1">
                <span className="text-gray-600 text-sm">{item.label}:</span>
                <span className="font-medium text-sm">{item.value}</span>
              </div>
            ))}
          </div>
          <Divider className="my-4" />
          <div className="flex flex-wrap gap-2">
            <Tag color="blue">React 19</Tag>
            <Tag color="geekblue">Ant Design</Tag>
            <Tag color="cyan">TailwindCSS</Tag>
            <Tag color="purple">TypeScript</Tag>
            <Tag color="green">Vite 7</Tag>
            <Tag color="orange">Zustand</Tag>
          </div>
        </Card>

        {/* 项目地址卡片 */}
        <Card
          variant={'borderless'}
          title={
            <div className="flex items-center">
              <LinkOutlined className="mr-2 text-green-500" />
              <span>{t('system.info.project.title')}</span>
            </div>
          }
        >
          <div className="space-y-3">
            {projectLinks.map((link, index) => (
              <div key={index} className="flex items-center">
                <span className="mr-2 text-gray-500">{link.icon}</span>
                <span className="text-gray-600 mr-2">{link.name}:</span>
                <a
                  href={link.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-blue-500 hover:underline"
                >
                  {link.url}
                </a>
              </div>
            ))}
          </div>
          <Divider className="my-4" />
          <div className="text-sm text-gray-500">
            {t('system.info.join')}
          </div>
        </Card>

        {/* 更新日志卡片 */}
        <Card
          variant={'borderless'}
          title={
            <div className="flex items-center">
              <HistoryOutlined className="mr-2 text-orange-500" />
              <span>{t('system.info.changelog.title')}</span>
            </div>
          }
        >
          <Timeline mode="left" className="mt-4">
            {changelog.map((log, index) => (
              <Timeline.Item key={index}>
                <div className="font-medium">{log.version}<span className="ml-2 text-gray-400 font-normal">{log.time} </span></div>
                <div className="text-gray-600">{log.content}</div>
              </Timeline.Item>
            ))}
          </Timeline>
        </Card>

        {/* 作者介绍卡片 */}
        <Card
          variant={'borderless'}
          title={
            <div className="flex items-center">
              <UserOutlined className="mr-2 text-purple-500" />
              <span>{t('system.info.author.title')}</span>
            </div>
          }
        >
          <div className="flex items-center mb-4">
            <Avatar
              src={authorInfo.avatar}
              size={64}
              className="mr-4 border-2 border-purple-200"
            />
            <div>
              <h3 className="text-lg font-bold">{authorInfo.name}</h3>
              <p className="text-gray-600">{authorInfo.role}</p>
            </div>
          </div>
          <Divider className="my-4" />
          <div className="space-y-3">
            {authorInfo.contact.map((item, index) => (
              <div key={index} className="flex items-center">
                <span className="mr-2 text-gray-500">{item.icon}</span>
                <span className="w-16 text-gray-600">{item.type}:</span>
                <span className="font-medium text-sm">
                  {item.value.startsWith('http') ? (
                    <a
                      href={item.value}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-blue-500 hover:underline"
                    >
                      {item.value}
                    </a>
                  ) : (
                    item.value
                  )}
                </span>
              </div>
            ))}
          </div>
        </Card>

        {/* 系统描述卡片 */}
        <Card
          variant={'borderless'}
          title={
            <div className="flex items-center">
              <FileTextOutlined className="mr-2 text-indigo-500" />
              <span>{t('system.info.desc.title')}</span>
            </div>
          }
          className="md:col-span-2 lg:col-span-1"
        >
          <div className="prose max-w-none">
            <p className="text-gray-700 leading-relaxed whitespace-pre-line">
              {t('system.info.desc.content')}
            </p>
          </div>
          <Divider className="my-4" />
          <div className="flex justify-center flex-wrap gap-2">
            <Tag color="magenta">{t('system.info.tag.enterprise')}</Tag>
            <Tag color="red">{t('system.info.tag.ready')}</Tag>
            <Tag color="volcano">{t('system.info.tag.scalable')}</Tag>
            <Tag color="gold">{t('system.info.tag.modern')}</Tag>
            <Tag color="blue">{t('system.info.tag.i18n')}</Tag>
            <Tag color="green">{t('system.info.tag.auth')}</Tag>
          </div>
        </Card>
      </div>
    </div>
  );
};

export default SystemInfoPage;
