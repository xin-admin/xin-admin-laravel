import { Tabs, Card } from 'antd';
import {UserOutlined, LockOutlined, IdcardOutlined, SnippetsOutlined} from '@ant-design/icons';
import { Outlet, useNavigate, useLocation } from 'react-router';
import {useEffect, useState} from 'react';
import {useTranslation} from "react-i18next";
import { useMobile } from '@/hooks/useMobile';

const UserSettingsPage = () => {
  const [activeTab, setActiveTab] = useState('/user/setting');
  const navigate = useNavigate();
  const location = useLocation();
  const {t} = useTranslation();
  const isMobile = useMobile();

  useEffect(() => {
    setActiveTab(location.pathname);
  }, [location]);

  const tabsList = [
    {
      label: (
        <span className="flex items-center">
          <UserOutlined className="mr-2" />
          {isMobile ? null : t("userSetting.baseInfo")}
        </span>
      ),
      key: '/user/setting',
    },
    {
      label: (
        <span className="flex items-center">
          <LockOutlined className="mr-2" />
          {isMobile ? null : t("userSetting.changePassword")}
        </span>
      ),
      key: '/user/setting/security',
    },
    {
      label: (
        <span className="flex items-center">
          <IdcardOutlined className="mr-2" />
          {isMobile ? null : t("userSetting.userVerification")}
        </span>
      ),
      key: '/user/setting/verification',
    },
    {
      label: (
        <span className="flex items-center">
          <SnippetsOutlined className="mr-2" />
          {isMobile ? null : t("userSetting.loginLog")}
        </span>
      ),
      key: '/user/setting/loginlog',
    },
  ]

  return (
    <Card
      title={t("userSetting.title")}
      variant={"borderless"}
      style={{ maxWidth: 800, width: '100%'}}
      styles={{ body: {paddingInline: 0, paddingRight: 25, display: "flex"}}}
    >
      <Tabs
        activeKey={activeTab}
        onChange={(key) => {
          navigate(key)
          setActiveTab(key)
        }}
        tabPosition="left"
        className="min-h-[500px]"
        defaultActiveKey="basic"
        items={tabsList}
      />
      <Outlet />
    </Card>
  );
};

export default UserSettingsPage;