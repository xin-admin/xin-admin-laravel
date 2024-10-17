import { LockOutlined, MailOutlined, PhoneFilled, PhoneOutlined, UserOutlined } from '@ant-design/icons';
import { LoginForm, ProFormText } from '@ant-design/pro-components';
import { message, theme } from 'antd';
import { useModel, useNavigate } from '@umijs/max';
import { reg } from '@/services/api';

export default () => {
  const { token } = theme.useToken();
  const { initialState } = useModel('@@initialState');
  const navigate = useNavigate();

  const statusRender = (value: any) => {
    const getStatus = () => {
      if (value && value.length > 12) {
        return 'ok';
      }
      if (value && value.length > 6) {
        return 'pass';
      }
      return 'poor';
    };
    const status = getStatus();
    if (status === 'pass') {
      return (
        <div style={{ color: token.colorWarning }}>
          强度：中
        </div>
      );
    }
    if (status === 'ok') {
      return (
        <div style={{ color: token.colorSuccess }}>
          强度：强
        </div>
      );
    }
    return (
      <div style={{ color: token.colorError }}>强度：弱</div>
    );
  }
  const handleSubmit = async (values: USER.UserLoginFrom) => {
    await reg(values);
    message.success('注册成功，请重新登录！');
    navigate('/client/login', {replace: true})
    return;
  };

  return (
    <div style={{ backgroundColor: token.colorBgContainer, maxWidth: 600, margin: '50px auto' }}>
      <LoginForm
        logo={initialState!.webSetting.logo || 'https://file.xinadmin.cn/file/favicons.ico'}
        title={'用户注册'}
        subTitle={'注册成为新用户，开启全新旅程！'}
        onFinish={handleSubmit}
      >
        <ProFormText
          name='username'
          fieldProps={{
            size: 'large',
            prefix: <UserOutlined className={'prefixIcon'} />,
          }}
          placeholder={'请输入用户名'}
          rules={[
            {
              required: true,
              message: '请输入用户名!',
            },
          ]}
        />
        <ProFormText.Password
          name='password'
          fieldProps={{
            size: 'large',
            prefix: <LockOutlined className={'prefixIcon'} />,
            strengthText: '密码只能为字母和数字，下划线_及破折号-，且长度最小为6位',
            statusRender: statusRender,
          }}
          placeholder={'请输入密码'}
          rules={[
            {
              required: true,
              message: '请输入密码！',
            },
          ]}
        />
        <ProFormText.Password
          name='rePassword'
          fieldProps={{
            size: 'large',
            prefix: <LockOutlined className={'prefixIcon'} />,
            strengthText: '密码只能为字母和数字，下划线_及破折号-，且长度最小为6位',
            statusRender: statusRender,
          }}
          placeholder={'请重复输入密码'}
          rules={[
            {
              required: true,
              message: '请重复输入密码！',
            },
            ({ getFieldValue }) => ({
              validator(_, value) {
                if (!value || getFieldValue('password') === value) {
                  return Promise.resolve();
                }
                return Promise.reject(new Error('重复密码不同!'));
              },
            }),
          ]}
        />
        <ProFormText
          fieldProps={{
            size: 'large',
            prefix: <MailOutlined className={'prefixIcon'} />,
          }}
          name='email'
          placeholder={'邮箱'}
          rules={[
            {
              required: true,
              message: '请输入邮箱！',
            },
            {
              pattern: /^[\w\\.-]+@[\w\\.-]+\.\w+$/,
              message: '邮箱格式错误！',
            },
          ]}
        />
        <ProFormText
          fieldProps={{
            size: 'large',
            prefix: <PhoneOutlined className={'prefixIcon'} />,
          }}
          name='mobile'
          placeholder={'手机号'}
          rules={[
            {
              required: true,
              message: '请输入手机号！',
            }
          ]}
        />
      </LoginForm>
    </div>
  );
};
