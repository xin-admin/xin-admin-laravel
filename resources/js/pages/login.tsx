import {Button, Card, Checkbox, Col, Flex, Form, Input, Row, Typography, message } from "antd";
import {LockOutlined, UserOutlined} from "@ant-design/icons";
import useUserStore from "@/stores/user";
import { router } from '@inertiajs/react';
const {Title} = Typography;

export default () => {
  const login = useUserStore(state => state.login);
  const [messageApi, contextHolder] = message.useMessage();
  const onFinish = async (values: any) => {
    console.log('Received values of form: ', values);
    await login(values);
    messageApi.open({
      type: 'loading',
      content: '正在跳转到首页...',
      duration: 2,
    });
    setTimeout(() => {
      const token = localStorage.getItem('token');
      router.visit('/dashboard', {
        viewTransition: true,
        headers: {
          'Authorization': `Bearer ${token}`
        }
      })
    }, 2000)
  };

  return (
    <Row className={'grid-pattern'}>
      {contextHolder}
      <Col lg={12} xs={24} className={'flex items-center justify-center min-h-screen'}>
        <Card classNames={{body: 'min-w-120 p-20'}} variant={'borderless'}>
          <div className={'flex justify-center w-full mb-10'}>
            <img src="https://xinadmin.cn/xinadmin.svg" className={'w-20'} alt="logo"/>
          </div>
          <Title level={1} className={'text-center mb-10'}>Xin Admin</Title>
          <Form
            size={'large'}
            name="login"
            method={'post'}
            initialValues={{ remember: true }}
            onFinish={onFinish}
          >
            <Form.Item
              name="username"
              rules={[{ required: true, message: 'Please input your Username!' }]}
            >
              <Input prefix={<UserOutlined />} placeholder="Username" />
            </Form.Item>
            <Form.Item
              name="password"
              rules={[{ required: true, message: 'Please input your Password!' }]}
            >
              <Input prefix={<LockOutlined />} type="password" placeholder="Password" />
            </Form.Item>
            <Form.Item>
              <Flex justify="space-between" align="center">
                <Form.Item name="remember" valuePropName="checked" noStyle>
                  <Checkbox>Remember me</Checkbox>
                </Form.Item>
                <a href="">Forgot password</a>
              </Flex>
            </Form.Item>

            <Form.Item>
              <Button block type="primary" htmlType="submit">
                Log in
              </Button>
            </Form.Item>
          </Form>
        </Card>
      </Col>
      <Col lg={12} xs={24} ></Col>
    </Row>
  )
}
