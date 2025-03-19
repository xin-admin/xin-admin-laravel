import { adminLogin } from '@/services';
import {
  AlipayOutlined,
  LockOutlined,
  QqOutlined,
  TaobaoOutlined,
  UserOutlined,
  WechatOutlined,
  WeiboOutlined,
} from '@ant-design/icons';
import { LoginFormPage, ProFormCheckbox, ProFormText } from '@ant-design/pro-components';
import { history, useModel } from '@umijs/max';
import { Divider, message, Space } from 'antd';
import type { CSSProperties } from 'react';
import React from 'react';

const iconStyle: CSSProperties = {
  color: 'rgba(0, 0, 0, 0.2)',
  fontSize: '18px',
  verticalAlign: 'middle',
  cursor: 'pointer',
};
const iconDivStyle: CSSProperties = {
  display: 'flex',
  justifyContent: 'center',
  alignItems: 'center',
  flexDirection: 'column',
  height: 40,
  width: 40,
  border: '1px solid #D4D8DD',
  borderRadius: '50%',
};

const Login: React.FC = () => {
  const { initialState } = useModel('@@initialState');
  if(localStorage.getItem('x-token')) {
    history.push('/');
  }
  const refreshUserInfo = useModel('userModel', ({ refreshUserInfoAsync }) => refreshUserInfoAsync);
  const refreshDict = useModel('dictModel', ({ refreshDict }) => refreshDict);
  const handleSubmit = async (values: USER.UserLoginFrom) => {
    // 登录
    const msg = await adminLogin({ ...values });
    localStorage.setItem('x-token', msg.data.token);
    localStorage.setItem('x-refresh-token', msg.data.refresh_token);
    await refreshUserInfo();
    refreshDict();
    message.success('登录成功！');
    history.push('/');
    return;
  };

  return (
    <LoginFormPage
      backgroundImageUrl="https://mdn.alipayobjects.com/huamei_gcee1x/afts/img/A*y0ZTS6WLwvgAAAAAAAAAAAAADml6AQ/fmt.webp"
      // backgroundVideoUrl="https://gw.alipayobjects.com/v/huamei_gcee1x/afts/video/jXRBRK_VAwoAAAAAAAAAAAAAK4eUAQBr"
      logo={initialState?.webSetting?.logo || 'https://file.xinadmin.cn/file/favicons.ico'}
      title={initialState?.webSetting?.title || 'Xin Admin'}
      subTitle={initialState?.webSetting?.subtitle || '用技术改变世界'}
      actions={
        <>
          <Divider plain>其他登录方式</Divider>
          <Space align="center" size={24} style={{ display: 'flex', justifyContent: 'center' }}>
            <div style={iconDivStyle}>
              <QqOutlined style={{ ...iconStyle, color: 'rgb(123, 212, 239)' }} />
            </div>
            <div style={iconDivStyle}>
              <WechatOutlined style={{ ...iconStyle, color: 'rgb(51, 204, 0)' }} />
            </div>
            <div style={iconDivStyle}>
              <AlipayOutlined style={{ ...iconStyle, color: '#1677FF' }} />
            </div>
            <div style={iconDivStyle}>
              <TaobaoOutlined style={{ ...iconStyle, color: '#FF6A10' }} />
            </div>
            <div style={iconDivStyle}>
              <WeiboOutlined style={{ ...iconStyle, color: '#e71f19' }} />
            </div>
          </Space>
        </>
      }
      onFinish={handleSubmit}
    >
      <ProFormText
        name="username"
        fieldProps={{
          size: 'large',
          prefix: <UserOutlined className={'prefixIcon'} />,
        }}
        placeholder={'用户名: admin'}
        rules={[{ required: true, message: '请输入用户名!' }]}
      />
      <ProFormText.Password
        name="password"
        fieldProps={{
          size: 'large',
          prefix: <LockOutlined className={'prefixIcon'} />,
        }}
        placeholder={'密码: 123456'}
        rules={[{ required: true, message: '请输入密码！' }]}
      />
      <div style={{ marginBlockEnd: 24 }}>
        <ProFormCheckbox noStyle name="autoLogin">自动登录</ProFormCheckbox>
        <a style={{ float: 'right' }}>忘记密码</a>
      </div>
    </LoginFormPage>
  );
};

export default Login;
