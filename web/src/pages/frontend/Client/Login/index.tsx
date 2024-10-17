import {
  AlipayOutlined,
  LockOutlined,
  QqOutlined,
  UserOutlined,
  WechatOutlined,
} from '@ant-design/icons';
import { LoginForm, ProFormCaptcha, ProFormText } from '@ant-design/pro-components';
import { useModel, useNavigate } from '@umijs/max';
import { Divider, message, Space, Tabs, theme } from 'antd';
import React, { useState } from 'react';
import { login } from '@/services/api';

export default () => {
  const navigate = useNavigate();
  const { initialState } = useModel('@@initialState');
  const { token } = theme.useToken();
  const [loginType, setLoginType] = useState<USER.LoginType>('account');
  // 登录
  const handleSubmit = async (values: USER.UserLoginFrom) => {
    const msg = await login({ ...values, loginType });
    // 记录令牌
    localStorage.setItem('app', 'api');
    localStorage.setItem('x-user-token', msg.data.token);
    localStorage.setItem('x-user-refresh-token', msg.data.refresh_token);
    message.success('登录成功！');
    setTimeout(() => {
      window.location.href = '/';
    }, 100)
    return;
  };

  const loginTypeItems = [
    {
      key: 'account',
      label: '账号密码登录'
    },
    {
      key: 'email',
      label: '邮箱登录'
    }
  ]

  const other = () => {
    message.warning('敬请期待').then()
  }

  return (
    <div style={{ backgroundColor: token.colorBgContainer, maxWidth: 600, margin: '50px auto' }}>
      <LoginForm
        logo={initialState!.webSetting.logo || 'https://file.xinadmin.cn/file/favicons.ico'}
        title={'用户登录'}
        subTitle={'登录平台账户，开启全新旅程！'}
        actions={
          <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', flexDirection: 'column'}}>
            <Divider plain>
              <span style={{ color: '#CCC', fontWeight: 'normal', fontSize: 14 }}>
                其他登录方式
              </span>
            </Divider>
            <Space align='center' size={24}>
              <QqOutlined onClick={other} style={{ fontSize: 20, color: '#4cafe9' }} />
              <WechatOutlined onClick={other} style={{ fontSize: 20, color: 'rgb(0,172,132)' }} />
              <AlipayOutlined onClick={other} style={{ fontSize: 20, color: '#1677FF' }} />
            </Space>
          </div>
        }
        onFinish={handleSubmit}
      >
        <Tabs
          centered
          activeKey={loginType}
          onChange={(activeKey) => setLoginType(activeKey as USER.LoginType)}
          items={loginTypeItems}
        >
        </Tabs>
        <ProFormText
          name="username"
          fieldProps={{
            size: 'large',
            prefix: <UserOutlined className={'prefixIcon'} />,
          }}
          placeholder={'用户名: user'}
          rules={[{required: true, message: '请输入用户名!',},]}
        />
        <ProFormText.Password
          name="password"
          fieldProps={{
            size: 'large',
            prefix: <LockOutlined className={'prefixIcon'} />,
          }}
          placeholder={'密码: 123456'}
          rules={[{required: true, message: '请输入密码！',},]}
        />
        <div style={{marginBlockEnd: 24}}>
          <a onClick={() => navigate('/client/reg')}>去注册</a>
          <a style={{float: 'right'}}>忘记密码</a>
        </div>
      </LoginForm>
    </div>
  );
};

