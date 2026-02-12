import { useState, useEffect } from 'react';
import { Tag, List, Avatar, Space, Typography } from 'antd';
import { UserOutlined, LaptopOutlined, EnvironmentOutlined, CheckCircleOutlined, CloseCircleOutlined } from '@ant-design/icons';
import type ISysLoginRecord from "@/domain/iSysLoginRecord.ts";
import { loginRecord } from "@/api/system/sysUser";
import dayjs from 'dayjs';
import {useTranslation} from "react-i18next";

const { Text } = Typography;

const LoginLogsPage = () => {
  const [logs, setLogs] = useState<ISysLoginRecord[]>([]);
  const {t} = useTranslation();

  useEffect(() => {
    loginRecord().then(res => setLogs(res.data.data || []));
  }, []);

  const BrowserIcon = ({ browser }: { browser?: string }) => {
    const icons: Record<string, string> = {
      Chrome: '🚀', Firefox: '🦊', Safari: '🍎', Edge: '🌊'
    };
    const icon = Object.keys(icons).find(key => browser?.includes(key));
    return <span>{icon ? icons[icon] : '💻'}</span>;
  };

  return (
    <List
      dataSource={logs}
      className={'w-full px-6 py-4 overflow-auto'}
      locale={{ emptyText: '暂无登录日志数据' }}
      renderItem={(log) => (
        <List.Item style={{minWidth: 600}}>
          <Avatar
            size="large"
            icon={<UserOutlined />}
            style={{
              backgroundColor: log.status === '0' ? '#87d068' : '#f56a00',
              marginRight: 16
            }}
          />
          <div className="flex-1">
            <div className="flex items-center mb-2">
              <Text strong className="mr-3 text-base">{log.username}</Text>
              {
                log.status === '0'
                  ? <Tag color="green" icon={<CheckCircleOutlined />}>{t("userSetting.loginLog.success")}</Tag>
                  : <Tag color="red" icon={<CloseCircleOutlined />}>{t("userSetting.loginLog.error")}</Tag>
              }
              <Text type="secondary" className="ml-auto">
                {dayjs(log.login_time).format('YYYY-MM-DD HH:mm:ss')}
              </Text>
            </div>
            <Space size="large" wrap>
              <div className="flex items-center">
                <LaptopOutlined className="mr-1 text-blue-500" />
                <Text type="secondary">{log.ipaddr}</Text>
              </div>
              <div className="flex items-center">
                <EnvironmentOutlined className="mr-1 text-green-500" />
                <Text type="secondary">{log.login_location}</Text>
              </div>
              <div className="flex items-center">
                <BrowserIcon browser={log.browser} />
                <Text type="secondary" className="ml-1">{log.browser}</Text>
              </div>
              <Text type="secondary">OS: {log.os}</Text>
            </Space>
          </div>
        </List.Item>
      )}
    />
  );
};

export default LoginLogsPage;
