import { useState, useEffect } from 'react';
import { Tabs, Card, Form, Input, Button, message, Radio, Upload, Avatar, List, Tag, Space, Typography } from 'antd';
import { UserOutlined, LockOutlined, SnippetsOutlined, UploadOutlined, LaptopOutlined, EnvironmentOutlined, CheckCircleOutlined, CloseCircleOutlined } from '@ant-design/icons';
import { useTranslation } from "react-i18next";
import useMobile from '@/hooks/useMobile';
import useAuthStore from "@/stores/user";
import { type InfoParams, updateInfo, type PasswordParams, updatePassword, loginRecord } from "@/api/system/sysUser";
import type ISysLoginRecord from "@/domain/iSysLoginRecord";
import type { UploadProps, FormProps } from 'antd';
import dayjs from 'dayjs';

const { Text } = Typography;

/** 基本信息 Tab */
const InfoTab = () => {
  const userInfo = useAuthStore(state => state.userinfo);
  const getInfo = useAuthStore(state => state.info);
  const [form] = Form.useForm();
  const [loading, setLoading] = useState<boolean>(false);
  const { t } = useTranslation();

  const uploadChange: UploadProps['onChange'] = async (info) => {
    if (info.file.status === 'done') {
      message.success(t("userSetting.baseInfo.avatarSuccess"));
      await getInfo();
    }
  }

  const onFinish: FormProps['onFinish'] = async (values: InfoParams) => {
    try {
      setLoading(true);
      await updateInfo(values);
      await getInfo();
      message.success(t("userSetting.baseInfo.success"));
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="w-full px-6 py-4">
      <div className="flex flex-col items-center mb-3">
        <Avatar size={120} src={userInfo?.avatar_url} className="mb-4 border-2 border-gray-200" />
        <Upload
          name="file"
          action={`${import.meta.env.VITE_BASE_URL}/admin/avatar`}
          headers={{ Authorization: `Bearer ${localStorage.getItem('token')}` }}
          showUploadList={false}
          onChange={uploadChange}
        >
          <Button icon={<UploadOutlined />}>{t("userSetting.baseInfo.updateAvatar")}</Button>
        </Upload>
        <p className="text-gray-500 text-sm mt-2">{t("userSetting.baseInfo.updateAvatarDesc")}</p>
      </div>
      <Form<InfoParams>
        form={form}
        layout="vertical"
        onFinish={onFinish}
        initialValues={{
          bio: userInfo?.bio,
          email: userInfo?.email,
          mobile: userInfo?.mobile,
          nickname: userInfo?.nickname,
          sex: userInfo?.sex,
          username: userInfo?.username,
        }}
      >
        <Form.Item label={t("userSetting.baseInfo.username")} name="username" rules={[{ required: true, message: t("userSetting.baseInfo.username.message") }]}>
          <Input disabled />
        </Form.Item>
        <Form.Item label={t("userSetting.baseInfo.nickname")} name="nickname" rules={[{ required: true, message: t("userSetting.baseInfo.nickname.message") }]}>
          <Input />
        </Form.Item>
        <Form.Item label={t("userSetting.baseInfo.sex")} name="sex">
          <Radio.Group options={[{ value: 0, label: t("userSetting.baseInfo.sex.0") }, { value: 1, label: t("userSetting.baseInfo.sex.1") }]} />
        </Form.Item>
        <Form.Item label={t("userSetting.baseInfo.bio")} name="bio">
          <Input.TextArea rows={4} />
        </Form.Item>
        <Form.Item label={t("userSetting.baseInfo.email")} name="email" rules={[{ type: 'email', message: t("userSetting.baseInfo.email.typeMessage") }, { required: true, message: t("userSetting.baseInfo.email.requiredMessage") }]}>
          <Input />
        </Form.Item>
        <Form.Item label={t("userSetting.baseInfo.mobile")} name="mobile" rules={[{ required: true, message: t("userSetting.baseInfo.mobile.message") }]}>
          <Input />
        </Form.Item>
        <Form.Item>
          <Button type="primary" htmlType="submit" size="large" loading={loading} block>
            {t("userSetting.baseInfo.submit")}
          </Button>
        </Form.Item>
      </Form>
    </div>
  );
};

/** 修改密码 Tab */
const SecurityTab = () => {
  const [form] = Form.useForm();
  const [loading, setLoading] = useState<boolean>(false);
  const { t } = useTranslation();

  const onFinish: FormProps['onFinish'] = async (values: PasswordParams) => {
    try {
      setLoading(true);
      await updatePassword(values);
      form.resetFields();
      message.success(t("userSetting.changePassword.success"));
    } finally {
      setLoading(false);
    }
  }

  return (
    <Form<PasswordParams> form={form} layout="vertical" onFinish={onFinish} className="w-full px-6 py-4">
      <Form.Item label={t("userSetting.changePassword.oldPassword")} name="oldPassword" rules={[{ required: true, message: t("userSetting.changePassword.oldPassword.message") }]}>
        <Input.Password />
      </Form.Item>
      <Form.Item label={t("userSetting.changePassword.newPassword")} name="newPassword" rules={[{ required: true, message: t("userSetting.changePassword.requiredMessage") }, { min: 8, message: t("userSetting.changePassword.minMessage") }]}>
        <Input.Password />
      </Form.Item>
      <Form.Item
        label={t("userSetting.changePassword.rePassword")}
        name="rePassword"
        dependencies={['newPassword']}
        rules={[
          { required: true, message: t("userSetting.changePassword.requiredMessage") },
          ({ getFieldValue }) => ({
            validator(_, value) {
              if (!value || getFieldValue('newPassword') === value) {
                return Promise.resolve();
              }
              return Promise.reject(new Error(t("userSetting.changePassword.rePassword.message")));
            },
          }),
        ]}
      >
        <Input.Password />
      </Form.Item>
      <Button type="primary" block htmlType="submit" size="large" loading={loading}>
        {t("userSetting.changePassword.submit")}
      </Button>
    </Form>
  );
};

/** 登录日志 Tab */
const LoginLogTab = () => {
  const [logs, setLogs] = useState<ISysLoginRecord[]>([]);
  const { t } = useTranslation();

  useEffect(() => {
    loginRecord().then(res => setLogs(res.data.data || []));
  }, []);

  const BrowserIcon = ({ browser }: { browser?: string }) => {
    const icons: Record<string, string> = { Chrome: '🚀', Firefox: '🦊', Safari: '🍎', Edge: '🌊' };
    const icon = Object.keys(icons).find(key => browser?.includes(key));
    return <span>{icon ? icons[icon] : '💻'}</span>;
  };

  return (
    <List
      dataSource={logs}
      className="w-full px-6 py-4 overflow-auto"
      locale={{ emptyText: t("userSetting.loginLog.empty") }}
      renderItem={(log) => (
        <List.Item style={{ minWidth: 600 }}>
          <Avatar
            size="large"
            icon={<UserOutlined />}
            style={{ backgroundColor: log.status === '0' ? '#87d068' : '#f56a00', marginRight: 16 }}
          />
          <div className="flex-1">
            <div className="flex items-center mb-2">
              <Text strong className="mr-3 text-base">{log.username}</Text>
              {log.status === '0'
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

/** 用户设置主页面 */
const UserSettingPage = () => {
  const [activeTab, setActiveTab] = useState('info');
  const { t } = useTranslation();
  const isMobile = useMobile();

  const tabsList = [
    {
      label: (
        <span className="flex items-center">
          <UserOutlined className="mr-2" />
          {isMobile ? null : t("userSetting.baseInfo")}
        </span>
      ),
      key: 'info',
      children: <InfoTab />,
    },
    {
      label: (
        <span className="flex items-center">
          <LockOutlined className="mr-2" />
          {isMobile ? null : t("userSetting.changePassword")}
        </span>
      ),
      key: 'security',
      children: <SecurityTab />,
    },
    {
      label: (
        <span className="flex items-center">
          <SnippetsOutlined className="mr-2" />
          {isMobile ? null : t("userSetting.loginLog")}
        </span>
      ),
      key: 'loginlog',
      children: <LoginLogTab />,
    },
  ];

  return (
    <Card
      title={t("userSetting.title")}
      variant="borderless"
      style={{ maxWidth: 800, width: '100%' }}
      styles={{ body: { paddingInline: 0, paddingRight: 25, display: "flex" } }}
    >
      <Tabs
        activeKey={activeTab}
        onChange={setActiveTab}
        tabPlacement="top"
        className="min-h-[500px] w-full"
        items={tabsList}
      />
    </Card>
  );
};

export default UserSettingPage;
