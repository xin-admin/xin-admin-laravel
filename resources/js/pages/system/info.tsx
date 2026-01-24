import { Card, Divider, Tag, Timeline, Avatar } from 'antd';
import {
  InfoCircleOutlined,
  LinkOutlined,
  HistoryOutlined,
  UserOutlined,
  FileTextOutlined,
  GithubOutlined,
  QqOutlined
} from '@ant-design/icons';

const SystemInfoPage = () => {
  // 系统基本信息
  const systemInfo = [
    { label: '系统名称', value: 'XinAdmin' },
    { label: '版本号', value: 'v2.0' },
    { label: '构建工具', value: 'Vite 7.1.5' },
    { label: '前端框架', value: 'React 19.1.0' },
    { label: 'UI框架', value: 'Ant Design 5.27.1' },
    { label: 'CSS框架', value: 'TailwindCSS 4.1.11' },
    { label: '路由管理', value: 'React Router 7.8.2' },
    { label: '状态管理', value: 'Zustand 5.0.8' },
    { label: 'TypeScript', value: '5.8.3' },
  ];

  // 项目地址
  const projectLinks = [
    { name: 'GitHub 仓库', url: 'https://github.com/xin-admin/xin-admin', icon: <GithubOutlined /> },
    { name: '在线文档', url: 'https://xinadmin.cn/ui/intro', icon: <FileTextOutlined /> },
    { name: '演示地址', url: 'https://ui.xinadmin.cn', icon: <LinkOutlined /> },
    { name: '问题反馈', url: 'https://github.com/xin-admin/xin-admin/issues', icon: <InfoCircleOutlined /> },
  ];

  // 更新日志
  const changelog = [
    { time: '2025-11', version: 'v2.0', content: '基于React 19、Vite 7、ReactRouter 7、zustand 5 和 TypeScript，重构项目模块' },
  ];

  // 作者信息
  const authorInfo = {
    name: 'XinAdmin 团队',
    avatar: 'https://file.xinadmin.cn/file/favicons.ico',
    role: '企业级中后台解决方案提供者',
    contact: [
      { type: 'GitHub', value: 'https://github.com/xin-admin/xin-admin', icon: <GithubOutlined /> },
      { type: 'QQ群', value: 'Xin Admin Official Community', icon: <QqOutlined /> },
      { type: '讨论区', value: 'https://github.com/xin-admin/xin-admin/discussions', icon: <LinkOutlined /> },
    ],
  };

  // 系统描述
  const systemDescription = `XinAdmin 是一个基于 Ant Design 设计规范的企业级中后台前端模板，采用最新的前端技术栈，包括 React 19、Vite 7、ReactRouter 7、zustand 5 和 TypeScript。

核心特性：
✨ 前沿技术栈 - React 19 + Vite 7 + TypeScript 5.8
👑 Ant Design 规范 - 模块化解决方案，减少冗余开发
🎢 清晰代码结构 - 语义化目录命名，独立命名空间
🎡 ReactRouter v7 - 支持后端动态路由，自动生成菜单
🧩 TailwindCSS - 原子化CSS，与 Ant Design 完美配合
🎡 内置国际化 - 支持中英日法俄5种语言
⛳ 完善的页面组件 - 包含错误页、布局组件等`;

  return (
    <div className="p-4 min-h-screen">
      <h1 className="text-2xl font-bold mb-6 text-gray-800">系统信息</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {/* 系统基本信息卡片 */}
        <Card
          variant={'borderless'}
          title={
            <div className="flex items-center">
              <InfoCircleOutlined className="mr-2 text-blue-500" />
              <span>系统基本信息</span>
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
              <span>项目地址</span>
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
            加入社区，获取最新动态和技术支持
          </div>
        </Card>

        {/* 更新日志卡片 */}
        <Card
          variant={'borderless'}
          title={
            <div className="flex items-center">
              <HistoryOutlined className="mr-2 text-orange-500" />
              <span>更新日志</span>
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
              <span>作者介绍</span>
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
                  {item.type === 'GitHub' || item.type === '讨论区' ? (
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
              <span>系统描述</span>
            </div>
          }
          className="md:col-span-2 lg:col-span-1"
        >
          <div className="prose max-w-none">
            <p className="text-gray-700 leading-relaxed whitespace-pre-line">
              {systemDescription}
            </p>
          </div>
          <Divider className="my-4" />
          <div className="flex justify-center flex-wrap gap-2">
            <Tag color="magenta">企业级</Tag>
            <Tag color="red">开箱即用</Tag>
            <Tag color="volcano">可扩展</Tag>
            <Tag color="gold">现代化</Tag>
            <Tag color="blue">国际化</Tag>
            <Tag color="green">权限控制</Tag>
          </div>
        </Card>
      </div>
    </div>
  );
};

export default SystemInfoPage;