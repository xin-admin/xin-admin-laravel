import {
  Descriptions,
  Card,
  Row,
  Col,
  Button,
  Space,
  Typography,
  Flex,
  Tag,
  Divider,
  Steps,
  Timeline,
  Table,
  Avatar,
} from 'antd';
import {
  ShoppingOutlined,
  PrinterOutlined,
  DownloadOutlined,
  CheckCircleOutlined,
  ClockCircleOutlined,
  UserOutlined,
} from '@ant-design/icons';

const { Title, Text } = Typography;

const Layout = () => {
  // 订单商品数据
  const productColumns = [
    {
      title: '商品名称',
      dataIndex: 'name',
      key: 'name',
    },
    {
      title: '商品编号',
      dataIndex: 'code',
      key: 'code',
    },
    {
      title: '数量',
      dataIndex: 'quantity',
      key: 'quantity',
    },
    {
      title: '单价',
      dataIndex: 'price',
      key: 'price',
      render: (price: number) => `¥${price.toFixed(2)}`,
    },
    {
      title: '小计',
      dataIndex: 'total',
      key: 'total',
      render: (total: number) => `¥${total.toFixed(2)}`,
    },
  ];

  const productData = [
    {
      key: '1',
      name: 'iPhone 15 Pro Max',
      code: 'IP15PM-256-BLK',
      quantity: 2,
      price: 9999,
      total: 19998,
    },
    {
      key: '2',
      name: 'AirPods Pro 2',
      code: 'APP2-WHT',
      quantity: 1,
      price: 1899,
      total: 1899,
    },
    {
      key: '3',
      name: 'MacBook Pro 16"',
      code: 'MBP16-M3-SLV',
      quantity: 1,
      price: 25999,
      total: 25999,
    },
  ];

  // 物流信息
  const logisticsData = [
    {
      time: '2026-01-01 15:30:00',
      status: '已签收',
      description: '您的订单已由本人签收,感谢您的购买',
    },
    {
      time: '2026-01-01 09:20:00',
      status: '派送中',
      description: '快递员正在为您派送 [北京市朝阳区] 快递员:李师傅 13800138000',
    },
    {
      time: '2025-12-31 18:45:00',
      status: '运输中',
      description: '您的包裹已到达 [北京分拨中心]',
    },
    {
      time: '2025-12-30 14:20:00',
      status: '已发货',
      description: '您的订单已从 [上海仓库] 发出,物流单号: SF1234567890',
    },
    {
      time: '2025-12-30 10:00:00',
      status: '已支付',
      description: '订单支付成功,等待商家发货',
    },
  ];

  return (
    <div style={{ minHeight: '100vh' }}>
      {/* 页面头部 */}
      <Card variant={'borderless'} style={{ marginBottom: 20 }}>
        <Flex justify="space-between" align="center">
          <div>
            <Title level={3} style={{ marginBottom: 4 }}>
              <ShoppingOutlined style={{ marginRight: 8 }} />
              订单详情
            </Title>
            <Text type="secondary">订单号: OD2026010100001 · 创建时间: 2025-12-30 09:45:32</Text>
          </div>
          <Space style={{ height: '100%' }}>
            <Button icon={<PrinterOutlined />}>打印订单</Button>
            <Button icon={<DownloadOutlined />}>导出订单</Button>
            <Button type="primary">联系客服</Button>
          </Space>
        </Flex>
      </Card>

      {/* 订单状态 */}
      <Card variant={'borderless'} style={{ marginBottom: 20 }}>
        <Steps
          current={4}
          items={[
            {
              title: '提交订单',
              description: '2025-12-30 09:45',
              icon: <CheckCircleOutlined />,
            },
            {
              title: '支付完成',
              description: '2025-12-30 10:00',
              icon: <CheckCircleOutlined />,
            },
            {
              title: '商家发货',
              description: '2025-12-30 14:20',
              icon: <CheckCircleOutlined />,
            },
            {
              title: '运输中',
              description: '2025-12-31 18:45',
              icon: <CheckCircleOutlined />,
            },
            {
              title: '已签收',
              description: '2026-01-01 15:30',
              icon: <CheckCircleOutlined />,
            },
          ]}
        />
      </Card>

      {/* 主内容区域 */}
      <Row gutter={[16, 16]}>
        {/* 订单基本信息 */}
        <Col xs={24} lg={16}>
          <Card
            variant={'borderless'}
            title="订单基本信息"
            style={{ marginBottom: 16 }}
          >
            <Descriptions column={{ xs: 1, sm: 2 }} bordered>
              <Descriptions.Item label="订单号">OD2026010100001</Descriptions.Item>
              <Descriptions.Item label="订单状态">
                <Tag color="success" icon={<CheckCircleOutlined />}>
                  已完成
                </Tag>
              </Descriptions.Item>
              <Descriptions.Item label="下单时间">2025-12-30 09:45:32</Descriptions.Item>
              <Descriptions.Item label="支付时间">2025-12-30 10:00:15</Descriptions.Item>
              <Descriptions.Item label="发货时间">2025-12-30 14:20:00</Descriptions.Item>
              <Descriptions.Item label="完成时间">2026-01-01 15:30:00</Descriptions.Item>
              <Descriptions.Item label="支付方式">
                <Tag color="blue">微信支付</Tag>
              </Descriptions.Item>
              <Descriptions.Item label="配送方式">顺丰速运</Descriptions.Item>
              <Descriptions.Item label="发票类型">电子发票</Descriptions.Item>
              <Descriptions.Item label="发票抬头">北京科技有限公司</Descriptions.Item>
            </Descriptions>
          </Card>

          {/* 收货信息 */}
          <Card
            variant={'borderless'}
            title="收货信息"
            style={{ marginBottom: 16 }}
          >
            <Descriptions column={{ xs: 1, sm: 2 }} bordered>
              <Descriptions.Item label="收货人">张三</Descriptions.Item>
              <Descriptions.Item label="联系电话">138****8888</Descriptions.Item>
              <Descriptions.Item label="收货地址" span={2}>
                北京市朝阳区建国路88号SOHO现代城A座2106室
              </Descriptions.Item>
            </Descriptions>
          </Card>

          {/* 商品信息 */}
          <Card variant={'borderless'} title="商品信息">
            <Table
              columns={productColumns}
              dataSource={productData}
              pagination={false}
              summary={() => (
                <Table.Summary>
                  <Table.Summary.Row>
                    <Table.Summary.Cell index={0} colSpan={4}>
                      <Text strong>合计</Text>
                    </Table.Summary.Cell>
                    <Table.Summary.Cell index={1}>
                      <Text strong style={{ color: '#f5222d', fontSize: 16 }}>
                        ¥47,896.00
                      </Text>
                    </Table.Summary.Cell>
                  </Table.Summary.Row>
                  <Table.Summary.Row>
                    <Table.Summary.Cell index={0} colSpan={4}>
                      <Text>运费</Text>
                    </Table.Summary.Cell>
                    <Table.Summary.Cell index={1}>
                      <Text>¥0.00</Text>
                    </Table.Summary.Cell>
                  </Table.Summary.Row>
                  <Table.Summary.Row>
                    <Table.Summary.Cell index={0} colSpan={4}>
                      <Text>优惠</Text>
                    </Table.Summary.Cell>
                    <Table.Summary.Cell index={1}>
                      <Text style={{ color: '#52c41a' }}>-¥500.00</Text>
                    </Table.Summary.Cell>
                  </Table.Summary.Row>
                  <Table.Summary.Row>
                    <Table.Summary.Cell index={0} colSpan={4}>
                      <Text strong style={{ fontSize: 16 }}>实付金额</Text>
                    </Table.Summary.Cell>
                    <Table.Summary.Cell index={1}>
                      <Text strong style={{ color: '#f5222d', fontSize: 18 }}>
                        ¥47,396.00
                      </Text>
                    </Table.Summary.Cell>
                  </Table.Summary.Row>
                </Table.Summary>
              )}
            />
          </Card>
        </Col>

        {/* 侧边栏信息 */}
        <Col xs={24} lg={8}>
          {/* 买家信息 */}
          <Card
            variant={'borderless'}
            title="买家信息"
            style={{ marginBottom: 16 }}
          >
            <Flex align="center" style={{ marginBottom: 16 }}>
              <Avatar size={48} icon={<UserOutlined />} />
              <div style={{ marginLeft: 12 }}>
                <Text strong>张三</Text>
                <br />
                <Text type="secondary" style={{ fontSize: 12 }}>
                  会员等级: VIP金卡
                </Text>
              </div>
            </Flex>
            <Divider style={{ margin: '12px 0' }} />
            <Descriptions column={1} size="small">
              <Descriptions.Item label="用户ID">U202512300001</Descriptions.Item>
              <Descriptions.Item label="手机号">138****8888</Descriptions.Item>
              <Descriptions.Item label="邮箱">zhangsan@example.com</Descriptions.Item>
              <Descriptions.Item label="历史订单">156 笔</Descriptions.Item>
              <Descriptions.Item label="累计消费">¥358,960</Descriptions.Item>
            </Descriptions>
          </Card>

          {/* 物流信息 */}
          <Card variant={'borderless'} title="物流追踪">
            <Text type="secondary" style={{ fontSize: 12 }}>
              物流公司: 顺丰速运
            </Text>
            <br />
            <Text type="secondary" style={{ fontSize: 12 }}>
              快递单号: SF1234567890
            </Text>
            <Divider style={{ margin: '12px 0' }} />
            <Timeline
              items={logisticsData.map((item) => ({
                color: item.status === '已签收' ? 'green' : 'blue',
                children: (
                  <div>
                    <Text strong>{item.status}</Text>
                    <br />
                    <Text type="secondary" style={{ fontSize: 12 }}>
                      {item.description}
                    </Text>
                    <br />
                    <Text type="secondary" style={{ fontSize: 12 }}>
                      <ClockCircleOutlined style={{ marginRight: 4 }} />
                      {item.time}
                    </Text>
                  </div>
                ),
              }))}
            />
          </Card>
        </Col>
      </Row>

      {/* 备注信息 */}
      <Card variant={'borderless'} title="订单备注" style={{ marginTop: 16 }}>
        <Text type="secondary">
          买家留言: 请在工作日配送,周末家里没人。如有问题请提前电话联系。
        </Text>
        <Divider />
        <Text type="secondary">
          商家备注: 已按照买家要求在工作日配送,客户体验良好。
        </Text>
      </Card>
    </div>
  );
};

export default Layout;
