import {Button, Card, Row, Col, Dropdown, Space, Typography, Flex, Statistic, Progress, Descriptions, Tag, Avatar} from "antd";
import {EllipsisOutlined, UserOutlined, TeamOutlined, ShoppingOutlined, RiseOutlined} from "@ant-design/icons";

const { Title } = Typography;

const BaseLayout = () => {
  return (
    <div style={{ minHeight: '100vh'}}>
      {/* 页面头部 */}
      <Card variant={"borderless"} style={{ marginBottom: 20}}>
        <Flex justify="space-between" align="center">
          <div>
            <Title level={3} style={{ marginBottom: 4 }}>页面标题</Title>
            <Typography.Text type="secondary">这是页面的描述信息,可以简要说明页面用途</Typography.Text>
          </div>
          <Space style={{ height: "100%" }}>
            <Button icon={<UserOutlined />}>操作一</Button>
            <Button icon={<TeamOutlined />}>操作二</Button>
            <Button type="primary" icon={<ShoppingOutlined />}>主要操作</Button>
            <Dropdown
              trigger={['click']}
              menu={{
                items: [
                  { label: '导出数据', key: '1', icon: <RiseOutlined /> },
                  { label: '批量操作', key: '2' },
                  { label: '更多设置', key: '3' },
                ],
              }}
            >
              <Button style={{ padding: '0 8px' }}>
                <EllipsisOutlined style={{ fontSize: 18 }} />
              </Button>
            </Dropdown>
          </Space>
        </Flex>
      </Card>

      {/* 数据统计卡片 */}
      <Row gutter={[16, 16]} style={{ marginBottom: 24 }}>
        <Col xs={24} sm={12} lg={6}>
          <Card variant={"borderless"}>
            <Statistic
              title="总用户数"
              value={11893}
              prefix={<UserOutlined style={{ color: '#1677ff' }} />}
              valueStyle={{ color: '#1677ff' }}
            />
            <div style={{ marginTop: 12 }}>
              <Tag color="success">+12.5%</Tag>
              <Typography.Text type="secondary" style={{ fontSize: 12 }}>较上周</Typography.Text>
            </div>
          </Card>
        </Col>
        <Col xs={24} sm={12} lg={6}>
          <Card variant={"borderless"}>
            <Statistic
              title="活跃用户"
              value={8234}
              prefix={<TeamOutlined style={{ color: '#52c41a' }} />}
              valueStyle={{ color: '#52c41a' }}
            />
            <div style={{ marginTop: 12 }}>
              <Tag color="success">+8.2%</Tag>
              <Typography.Text type="secondary" style={{ fontSize: 12 }}>较上周</Typography.Text>
            </div>
          </Card>
        </Col>
        <Col xs={24} sm={12} lg={6}>
          <Card variant={"borderless"}>
            <Statistic
              title="总订单"
              value={32567}
              prefix={<ShoppingOutlined style={{ color: '#faad14' }} />}
              valueStyle={{ color: '#faad14' }}
            />
            <div style={{ marginTop: 12 }}>
              <Tag color="warning">+5.3%</Tag>
              <Typography.Text type="secondary" style={{ fontSize: 12 }}>较上周</Typography.Text>
            </div>
          </Card>
        </Col>
        <Col xs={24} sm={12} lg={6}>
          <Card variant={"borderless"}>
            <Statistic
              title="总收入"
              value={98234}
              prefix="¥"
              valueStyle={{ color: '#f5222d' }}
            />
            <div style={{ marginTop: 12 }}>
              <Tag color="error">-2.1%</Tag>
              <Typography.Text type="secondary" style={{ fontSize: 12 }}>较上周</Typography.Text>
            </div>
          </Card>
        </Col>
      </Row>

      {/* 主内容区域 */}
      <Card
        variant={"borderless"}
        tabList={[
          {
            label: '基本信息',
            key: 'base',
          },
          {
            label: '详细信息',
            key: 'info'
          },
          {
            label: '数据分析',
            key: 'analysis'
          }
        ]}
        activeTabKey="base"
      >
        <Row gutter={[16, 16]} style={{ marginBottom: 24 }}>
          {/* 用户信息卡片 */}
          <Col xs={24} lg={24}>
            <Card
              title={<span><UserOutlined style={{ marginRight: 8 }} />用户信息概览</span>}
              style={{
                background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                color: '#fff',
                borderRadius: 8
              }}
              styles={{
                header: {
                  color: '#fff',
                  borderBottom: '1px solid rgba(255,255,255,0.2)'
                }
              }}
            >
              <Row gutter={[16, 16]}>
                <Col span={6}>
                  <Avatar size={64} icon={<UserOutlined />} />
                </Col>
                <Col span={18}>
                  <Typography.Title level={5} style={{ color: '#fff', marginTop: 0 }}>
                    张三 / Zhang San
                  </Typography.Title>
                  <Typography.Text style={{ color: 'rgba(255,255,255,0.85)' }}>
                    高级管理员 · 北京市朝阳区 · 在职
                  </Typography.Text>
                </Col>
              </Row>
            </Card>
          </Col>

          {/* 详细信息卡片 */}
          <Col xs={24} lg={16}>
            <Card
              title="详细信息"
              style={{ height: '100%', borderRadius: 8 }}
            >
              <Descriptions column={{ xs: 1, sm: 2 }}>
                <Descriptions.Item label="用户名">zhangsan</Descriptions.Item>
                <Descriptions.Item label="手机号">138****8888</Descriptions.Item>
                <Descriptions.Item label="邮箱">zhangsan@example.com</Descriptions.Item>
                <Descriptions.Item label="部门">技术部</Descriptions.Item>
                <Descriptions.Item label="职位">高级工程师</Descriptions.Item>
                <Descriptions.Item label="入职时间">2023-01-15</Descriptions.Item>
                <Descriptions.Item label="状态">
                  <Tag color="success">正常</Tag>
                </Descriptions.Item>
                <Descriptions.Item label="权限等级">
                  <Tag color="blue">管理员</Tag>
                </Descriptions.Item>
              </Descriptions>
            </Card>
          </Col>

          {/* 进度统计卡片 */}
          <Col xs={24} lg={8}>
            <Card
              title="任务完成度"
              style={{ height: '100%', borderRadius: 8 }}
            >
              <div style={{ marginBottom: 20 }}>
                <div style={{ marginBottom: 8 }}>
                  <span>本周任务</span>
                  <span style={{ float: 'right', fontWeight: 600 }}>75%</span>
                </div>
                <Progress percent={75} strokeColor="#52c41a" />
              </div>
              <div style={{ marginBottom: 20 }}>
                <div style={{ marginBottom: 8 }}>
                  <span>本月目标</span>
                  <span style={{ float: 'right', fontWeight: 600 }}>60%</span>
                </div>
                <Progress percent={60} strokeColor="#1677ff" />
              </div>
              <div>
                <div style={{ marginBottom: 8 }}>
                  <span>年度KPI</span>
                  <span style={{ float: 'right', fontWeight: 600 }}>45%</span>
                </div>
                <Progress percent={45} strokeColor="#faad14" />
              </div>
            </Card>
          </Col>
        </Row>

        {/* 底部操作按钮 */}
        <Flex justify="space-between" align="center">
          <Space>
            <Button size="large">重置</Button>
            <Button type="primary" size="large">保存</Button>
            <Button type="primary" size="large" ghost>提交审核</Button>
          </Space>
          <Typography.Text type="secondary">
            最后更新时间: 2026-01-01 10:30:00
          </Typography.Text>
        </Flex>
      </Card>
    </div>
  );
};

export default BaseLayout;
