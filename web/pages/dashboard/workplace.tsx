import {Card, Avatar, List, Badge, Tag, Divider, Typography, Space, Empty} from 'antd';
import {
  ProjectOutlined,
  TeamOutlined,
  BellOutlined,
  ClockCircleOutlined,
  MailOutlined,
  PhoneOutlined,
  EnvironmentOutlined,
  LinkOutlined,
  SmileOutlined,
  AppstoreOutlined,
  ShopOutlined,
  FileTextOutlined,
} from '@ant-design/icons';
import { useTranslation } from 'react-i18next';
import useAuthStore from "@/stores/user";
import type {ReactNode} from "react";
const { Meta } = Card;
const { Text, Title } = Typography;

interface AppType {
  id: number;
  name: string;
  description: string;
  icon: ReactNode;
  badge?: string;
  color: string;
}

const PersonalCenter = () => {
  const { t } = useTranslation();
  // 用户信息数据
  const userInfo = useAuthStore(state => state.userinfo);
  if (! userInfo ) return <></>;

  // 标签页列表数据
  const tabList = [
    {
      key: "projects",
      label: <><ProjectOutlined className={'mr-2'}/> {t('dashboard.workplace.myProjects')} </>,
    },
    {
      key: "teams",
      label: <><TeamOutlined className={'mr-2'}/> {t('dashboard.workplace.myTeams')} </>,
    },
    {
      key: "activities",
      label: <><ClockCircleOutlined className={'mr-2'}/> {t('dashboard.workplace.latestActivities')} </>,
    },
    {
      key: "notifications",
      label: (
        <Badge count={1} offset={[10, 0]}>
          <span>
            <BellOutlined className={'mr-2'} />
            {t('dashboard.workplace.notifications')}
          </span>
        </Badge>
      )
    },
  ]
  // 我的项目数据
  const projects = [
    {
      id: 1,
      name: t('dashboard.workplace.project1.name'),
      description: t('dashboard.workplace.project1.desc'),
      status: t('dashboard.workplace.status.inProgress'),
      progress: 65,
      members: 5,
    },
    {
      id: 2,
      name: t('dashboard.workplace.project2.name'),
      description: t('dashboard.workplace.project2.desc'),
      status: t('dashboard.workplace.status.completed'),
      progress: 100,
      members: 3,
    },
    {
      id: 3,
      name: t('dashboard.workplace.project3.name'),
      description: t('dashboard.workplace.project3.desc'),
      status: t('dashboard.workplace.status.planning'),
      progress: 10,
      members: 2,
    },
    {
      id: 4,
      name: t('dashboard.workplace.project4.name'),
      description: t('dashboard.workplace.project4.desc'),
      status: '2023-06-10',
      progress: 10,
      members: 2,
    },
    {
      id: 5,
      name: t('dashboard.workplace.project5.name'),
      description: t('dashboard.workplace.project5.desc'),
      status: '2023-06-08',
      progress: 15,
      members: 5,
    },
    {
      id: 6,
      name: t('dashboard.workplace.project6.name'),
      description: t('dashboard.workplace.project6.desc'),
      status: '2023-06-05',
      progress: 60,
      members: 3,
    },
  ];
  // APP 应用列表
  const apps: AppType[] = [
    {
      id: 1,
      name: t('dashboard.workplace.appStore'),
      icon: <AppstoreOutlined />,
      description: t('dashboard.workplace.appStore.desc'),
      badge: t('dashboard.workplace.badge.new'),
      color: '#1890ff',
    },
    {
      id: 2,
      name: t('dashboard.workplace.mallSystem'),
      icon: <ShopOutlined />,
      description: t('dashboard.workplace.mallSystem.desc'),
      color: '#52c41a',
    },
    {
      id: 3,
      name: t('dashboard.workplace.collaboration'),
      icon: <TeamOutlined />,
      description: t('dashboard.workplace.collaboration.desc'),
      color: '#faad14',
    },
    {
      id: 4,
      name: t('dashboard.workplace.docCenter'),
      icon: <FileTextOutlined />,
      description: t('dashboard.workplace.docCenter.desc'),
      color: '#13c2c2',
    }
  ];
  // 获取当前时间问候语
  const getGreeting = () => {
    const hour = new Date().getHours();
    if (hour < 12) return t('dashboard.workplace.greeting.morning');
    if (hour < 18) return t('dashboard.workplace.greeting.afternoon');
    return t('dashboard.workplace.greeting.evening');
  };
  // APP 应用卡片
  const AppCard = (props: {app: AppType}) => (
    <Card
      hoverable
      variant={'borderless'}
      cover={
        <div
          className="flex items-center justify-center h-32"
          style={{ backgroundColor: `${props.app.color}20` }} // 20表示透明度
        >
          <Avatar
            icon={props.app.icon}
            size={64}
            style={{
              backgroundColor: props.app.color,
              color: '#fff',
              fontSize: '28px',
            }}
          />
        </div>
      }
    >
      <Meta
        title={<span className="font-semibold">{props.app.name}</span>}
        description={props.app.description}
      />
    </Card>
  )

  return (
    <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
      {/* 左侧布局显示内容 */}
      <div className="lg:col-span-3 space-y-6">
        <Card variant={'borderless'} styles={{body: {padding: 0}}} className={'overflow-hidden'}>
          <div className="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center p-8">
            <Space size="large" className="w-full">
              <Avatar
                size={64}
                src={userInfo.avatar_url}
                icon={<SmileOutlined />}
                className="border-2 border-white shadow"
              />
              <div className="flex-1">
                <Title level={3} className="!text-white !mb-1">
                  {getGreeting()}，{userInfo.nickname}，{t('dashboard.workplace.welcome')}
                </Title>
              </div>
              <div className="hidden md:block">
                <Text className="text-white/80 text-lg">
                  {t('dashboard.workplace.today')} {new Date().toLocaleDateString('zh-CN', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric',
                  weekday: 'long'
                })}
                </Text>
              </div>
            </Space>
          </div>
        </Card>
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          {apps.map((app) => (
            <div key={app.id}>
              { app.badge ?
                <Badge.Ribbon text={app.badge} color={app.color}>
                  <AppCard app={app}></AppCard>
                </Badge.Ribbon>
                :
                <AppCard app={app} ></AppCard>
              }
            </div>
          ))}
        </div>
        <Card variant={'borderless'} defaultActiveTabKey="projects" tabList={tabList}>
          <List
            itemLayout="horizontal"
            dataSource={projects}
            renderItem={(item) => (
              <List.Item>
                <List.Item.Meta
                  avatar={<Avatar icon={<ProjectOutlined />} />}
                  title={<a href="#">{item.name}</a>}
                  description={item.description}
                />
                <div className="flex flex-col items-end">
                  <Tag color={item.status === t('dashboard.workplace.status.completed') ? 'success' : item.status === t('dashboard.workplace.status.inProgress') ? 'processing' : 'default'}>
                    {item.status}
                  </Tag>
                  <div className="mt-2">
                    <span className="text-gray-500 mr-2">{t('dashboard.workplace.progress')}: {item.progress}%</span>
                    <span className="text-gray-500">{t('dashboard.workplace.members')}: {item.members}{t('dashboard.workplace.people')}</span>
                  </div>
                </div>
              </List.Item>
            )}
          />
        </Card>
      </div>

      {/* 右侧布局显示内容 */}
      <div className="lg:col-span-1">
        <Card variant={'borderless'} className="mb-5">
          <div className="flex flex-col items-center">
            <Avatar size={100} src={userInfo.avatar_url} className="mb-4" />
            <h2 className="text-xl font-bold mb-2">{userInfo.nickname}</h2>
            <Tag color="blue" className="mb-4">
              {userInfo.dept_name}
            </Tag>
            <p className="text-gray-700 mb-2 text-center">hello</p>
            <Divider className="my-3 mb-4" />
            <div className="w-full space-y-3">
              <div className="flex items-center text-gray-600">
                <MailOutlined className="mr-2" />
                <span>{userInfo.email}</span>
              </div>
              <div className="flex items-center text-gray-600">
                <PhoneOutlined className="mr-2" />
                <span>{userInfo.mobile}</span>
              </div>
              <div className="flex items-center text-gray-600">
                <EnvironmentOutlined className="mr-2" />
                <span>{t('dashboard.workplace.location')}</span>
              </div>
              <div className="flex items-center text-gray-600">
                <LinkOutlined className="mr-2" />
                <a target="_blank" rel="noopener noreferrer" className="text-blue-500">
                  123
                </a>
              </div>
            </div>
          </div>
        </Card>
        <Card variant={'borderless'} title={t('dashboard.workplace.recentVisits')}>
          <Empty/>
        </Card>
      </div>
    </div>
  );
};

export default PersonalCenter;