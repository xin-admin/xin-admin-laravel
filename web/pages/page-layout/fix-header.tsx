import {
  Card,
  Row,
  Col,
  Button,
  Space,
  Typography,
  Descriptions,
  Steps,
  Timeline,
  Tag,
  Avatar,
  Form,
  Input,
  Badge,
  Divider,
  Flex,
} from "antd";
import React from "react";
import {
  AuditOutlined,
  CheckCircleOutlined,
  CloseCircleOutlined,
  ClockCircleOutlined,
  FileDoneOutlined,
  EditOutlined,
  PrinterOutlined,
  ExportOutlined,
} from "@ant-design/icons";

const { Title, Text, Paragraph } = Typography;
const { TextArea } = Input;

const FixHeader = () => {
  const [activeTab, setActiveTab] = React.useState('1');

  // 审批历史数据
  const approvalHistory = [
    {
      time: '2026-01-01 14:30:00',
      approver: '王总',
      role: '总经理',
      status: 'approved',
      statusText: '已通过',
      comment: '同意该采购申请，请采购部门尽快落实。',
      avatar: 'W',
    },
    {
      time: '2026-01-01 11:20:00',
      approver: '李经理',
      role: '财务经理',
      status: 'approved',
      statusText: '已通过',
      comment: '预算充足，财务审批通过。',
      avatar: 'L',
    },
    {
      time: '2026-01-01 10:15:00',
      approver: '赵主管',
      role: '部门主管',
      status: 'approved',
      statusText: '已通过',
      comment: '该采购申请合理，同意提交上级审批。',
      avatar: 'Z',
    },
    {
      time: '2025-12-31 16:45:00',
      approver: '张三',
      role: '申请人',
      status: 'submitted',
      statusText: '已提交',
      comment: '因公司业务需要，申请采购以下办公设备。',
      avatar: 'Z',
    },
  ];

  // 审批明细数据
  const approvalItems = [
    { name: 'MacBook Pro 16"', quantity: 5, price: 25999, total: 129995 },
    { name: 'Dell 显示器 27"', quantity: 10, price: 2999, total: 29990 },
    { name: '人体工学椅', quantity: 10, price: 1899, total: 18990 },
    { name: '会议摄像头', quantity: 2, price: 3999, total: 7998 },
  ];

  const totalAmount = approvalItems.reduce((sum, item) => sum + item.total, 0);

  return (
    <div>
      <Card style={{ marginBottom: 16, backgroundColor: '#fff' }}>
        <Flex justify="space-between" align="center" style={{ marginBottom: 12 }}>
          <div>
            <Title level={3} style={{ margin: 0 }}>
              <AuditOutlined style={{ marginRight: 8 }} />
              采购审批申请
            </Title>
            <Text type="secondary" style={{ fontSize: 12 }}>
              申请单号: PR2025123100001 · 创建时间: 2025-12-31 16:45:32
            </Text>
          </div>
          <Space>
            <Button icon={<PrinterOutlined />}>打印</Button>
            <Button icon={<ExportOutlined />}>导出</Button>
            <Button type="primary" icon={<CheckCircleOutlined />}>
              审批通过
            </Button>
            <Button danger icon={<CloseCircleOutlined />}>
              驳回
            </Button>
          </Space>
        </Flex>
      </Card>

      <Card
        style={{ marginBottom: 16, backgroundColor: '#fff' }}
        tabList={[
          { label: <span><FileDoneOutlined /> 审批详情</span>, key: '1' },
          { label: <span><ClockCircleOutlined /> 审批进度</span>, key: '2' },
          { label: <span><EditOutlined /> 审批意见</span>, key: '3' },
        ]}
        activeTabKey={activeTab}
        onTabChange={setActiveTab}
      >
        {/* 审批详情标签页 */}
        {activeTab === '1' && (
          <Row gutter={[20, 20]}>
            {/* 申请信息 */}
            <Col xs={24} lg={16}>
              <div style={{ marginBottom: 16 }}>
                <Flex justify="space-between" align="center" style={{ marginBottom: 16 }}>
                  <Title level={5} style={{ margin: 0 }}>申请信息</Title>
                  <Tag color="success" icon={<CheckCircleOutlined />}>
                    审批通过
                  </Tag>
                </Flex>
                <Descriptions column={{ xs: 1, sm: 2 }} bordered>
                  <Descriptions.Item label="申请单号">PR2025123100001</Descriptions.Item>
                  <Descriptions.Item label="申请状态">
                    <Badge status="success" text="已通过" />
                  </Descriptions.Item>
                  <Descriptions.Item label="申请人">张三</Descriptions.Item>
                  <Descriptions.Item label="申请部门">技术研发部</Descriptions.Item>
                  <Descriptions.Item label="联系电话">138****8888</Descriptions.Item>
                  <Descriptions.Item label="邮箱">zhangsan@example.com</Descriptions.Item>
                  <Descriptions.Item label="申请时间">2025-12-31 16:45:32</Descriptions.Item>
                  <Descriptions.Item label="完成时间">2026-01-01 14:30:00</Descriptions.Item>
                  <Descriptions.Item label="申请类型">
                    <Tag color="blue">办公设备采购</Tag>
                  </Descriptions.Item>
                  <Descriptions.Item label="紧急程度">
                    <Tag color="orange">普通</Tag>
                  </Descriptions.Item>
                  <Descriptions.Item label="预算编号">BUD-2026-Q1-001</Descriptions.Item>
                  <Descriptions.Item label="成本中心">CC-RD-001</Descriptions.Item>
                  <Descriptions.Item label="申请事由" span={2}>
                    因公司业务扩展，技术研发部新增10名员工，需采购相应办公设备以满足日常工作需求。
                  </Descriptions.Item>
                </Descriptions>
              </div>

              {/* 采购明细 */}
              <div style={{ marginBottom: 16 }}>
                <Title level={5} style={{ marginBottom: 16 }}>采购明细</Title>
                <div style={{ overflowX: 'auto' }}>
                  <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                    <thead>
                    <tr style={{ backgroundColor: '#fafafa' }}>
                      <th style={{ padding: '12px', textAlign: 'left', border: '1px solid #f0f0f0' }}>物品名称</th>
                      <th style={{ padding: '12px', textAlign: 'center', border: '1px solid #f0f0f0' }}>数量</th>
                      <th style={{ padding: '12px', textAlign: 'right', border: '1px solid #f0f0f0' }}>单价(¥)</th>
                      <th style={{ padding: '12px', textAlign: 'right', border: '1px solid #f0f0f0' }}>小计(¥)</th>
                    </tr>
                    </thead>
                    <tbody>
                    {approvalItems.map((item, index) => (
                      <tr key={index}>
                        <td style={{ padding: '12px', border: '1px solid #f0f0f0' }}>{item.name}</td>
                        <td style={{ padding: '12px', textAlign: 'center', border: '1px solid #f0f0f0' }}>{item.quantity}</td>
                        <td style={{ padding: '12px', textAlign: 'right', border: '1px solid #f0f0f0' }}>{item.price.toLocaleString()}</td>
                        <td style={{ padding: '12px', textAlign: 'right', border: '1px solid #f0f0f0' }}>{item.total.toLocaleString()}</td>
                      </tr>
                    ))}
                    </tbody>
                    <tfoot>
                    <tr style={{ backgroundColor: '#fafafa', fontWeight: 600 }}>
                      <td colSpan={3} style={{ padding: '12px', textAlign: 'right', border: '1px solid #f0f0f0' }}>合计金额:</td>
                      <td style={{ padding: '12px', textAlign: 'right', border: '1px solid #f0f0f0', color: '#f5222d', fontSize: 16 }}>
                        ¥{totalAmount.toLocaleString()}
                      </td>
                    </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              {/* 附件信息 */}
              <div>
                <Title level={5} style={{ marginBottom: 16 }}>附件材料</Title>
                <Space direction="vertical" style={{ width: '100%' }}>
                  <Flex justify="space-between" align="center">
                    <Space>
                      <FileDoneOutlined style={{ fontSize: 16, color: '#1677ff' }} />
                      <Text>采购申请表.pdf</Text>
                      <Text type="secondary" style={{ fontSize: 12 }}>(2.3 MB)</Text>
                    </Space>
                    <Button type="link" size="small">下载</Button>
                  </Flex>
                  <Divider style={{ margin: '8px 0' }} />
                  <Flex justify="space-between" align="center">
                    <Space>
                      <FileDoneOutlined style={{ fontSize: 16, color: '#1677ff' }} />
                      <Text>设备报价单.xlsx</Text>
                      <Text type="secondary" style={{ fontSize: 12 }}>(1.8 MB)</Text>
                    </Space>
                    <Button type="link" size="small">下载</Button>
                  </Flex>
                  <Divider style={{ margin: '8px 0' }} />
                  <Flex justify="space-between" align="center">
                    <Space>
                      <FileDoneOutlined style={{ fontSize: 16, color: '#1677ff' }} />
                      <Text>预算说明文档.docx</Text>
                      <Text type="secondary" style={{ fontSize: 12 }}>(856 KB)</Text>
                    </Space>
                    <Button type="link" size="small">下载</Button>
                  </Flex>
                </Space>
              </div>
            </Col>

            {/* 侧边栏 */}
            <Col xs={24} lg={8}>
              {/* 申请人信息 */}
              <Card title="申请人信息" style={{ marginBottom: 16 }}>
                <Flex align="center" style={{ marginBottom: 16 }}>
                  <Avatar size={48} style={{ backgroundColor: '#1677ff' }}>
                    张三
                  </Avatar>
                  <div style={{ marginLeft: 12 }}>
                    <Text strong>张三</Text>
                    <br />
                    <Text type="secondary" style={{ fontSize: 12 }}>技术研发部 · 高级工程师</Text>
                  </div>
                </Flex>
                <Divider style={{ margin: '12px 0' }} />
                <Descriptions column={1} size="small">
                  <Descriptions.Item label="员工编号">EMP202301001</Descriptions.Item>
                  <Descriptions.Item label="联系电话">138****8888</Descriptions.Item>
                  <Descriptions.Item label="电子邮箱">zhangsan@example.com</Descriptions.Item>
                  <Descriptions.Item label="所属部门">技术研发部</Descriptions.Item>
                  <Descriptions.Item label="直属领导">赵主管</Descriptions.Item>
                </Descriptions>
              </Card>

              {/* 审批流程 */}
              <Card title="审批流程">
                <Steps
                  direction="vertical"
                  current={3}
                  items={[
                    {
                      title: '申请人提交',
                      description: (
                        <div>
                          <Text>张三 · 技术研发部</Text>
                          <br />
                          <Text type="secondary" style={{ fontSize: 12 }}>2025-12-31 16:45</Text>
                        </div>
                      ),
                      status: 'finish',
                      icon: <CheckCircleOutlined />,
                    },
                    {
                      title: '部门审批',
                      description: (
                        <div>
                          <Text>赵主管 · 部门主管</Text>
                          <br />
                          <Text type="secondary" style={{ fontSize: 12 }}>2026-01-01 10:15</Text>
                          <br />
                          <Tag color="success" style={{ marginTop: 4 }}>已通过</Tag>
                        </div>
                      ),
                      status: 'finish',
                      icon: <CheckCircleOutlined />,
                    },
                    {
                      title: '财务审批',
                      description: (
                        <div>
                          <Text>李经理 · 财务经理</Text>
                          <br />
                          <Text type="secondary" style={{ fontSize: 12 }}>2026-01-01 11:20</Text>
                          <br />
                          <Tag color="success" style={{ marginTop: 4 }}>已通过</Tag>
                        </div>
                      ),
                      status: 'finish',
                      icon: <CheckCircleOutlined />,
                    },
                    {
                      title: '总经理审批',
                      description: (
                        <div>
                          <Text>王总 · 总经理</Text>
                          <br />
                          <Text type="secondary" style={{ fontSize: 12 }}>2026-01-01 14:30</Text>
                          <br />
                          <Tag color="success" style={{ marginTop: 4 }}>已通过</Tag>
                        </div>
                      ),
                      status: 'finish',
                      icon: <CheckCircleOutlined />,
                    },
                  ]}
                />
              </Card>
            </Col>
          </Row>
        )}

        {/* 审批进度标签页 */}
        {activeTab === '2' && (
          <Timeline
            style={{ maxWidth: 600, paddingTop: 20 }}
            mode="left"
            items={approvalHistory.map((item) => ({
              color: item.status === 'approved' ? 'green' : 'blue',
              label: <Text type="secondary">{item.time}</Text>,
              children: (
                <Card size="small" style={{ marginBottom: 8 }}>
                  <Flex align="center" style={{ marginBottom: 8 }}>
                    <Avatar style={{ backgroundColor: item.status === 'approved' ? '#52c41a' : '#1677ff' }}>
                      {item.avatar}
                    </Avatar>
                    <div style={{ marginLeft: 12 }}>
                      <Text strong>{item.approver}</Text>
                      <Text type="secondary" style={{ marginLeft: 8, fontSize: 12 }}>({item.role})</Text>
                      <br />
                      <Tag
                        color={item.status === 'approved' ? 'success' : 'processing'}
                        style={{ marginTop: 4 }}
                      >
                        {item.statusText}
                      </Tag>
                    </div>
                  </Flex>
                  <Paragraph
                    type="secondary"
                    style={{ marginBottom: 0, paddingLeft: 60, fontSize: 13 }}
                  >
                    {item.comment}
                  </Paragraph>
                </Card>
              ),
            }))}
          />
        )}

        {/* 审批意见标签页 */}
        {activeTab === '3' && (
          <Row gutter={[16, 16]}>
            <Col xs={24} lg={16}>
              <Card title="填写审批意见">
                <Form layout="vertical">
                  <Form.Item label="审批结果" required>
                    <Space>
                      <Button type="primary" icon={<CheckCircleOutlined />} size="large">
                        通过
                      </Button>
                      <Button danger icon={<CloseCircleOutlined />} size="large">
                        驳回
                      </Button>
                      <Button icon={<EditOutlined />} size="large">
                        转审
                      </Button>
                    </Space>
                  </Form.Item>
                  <Form.Item label="审批意见" required>
                    <TextArea
                      rows={6}
                      placeholder="请输入您的审批意见..."
                      maxLength={500}
                      showCount
                    />
                  </Form.Item>
                  <Form.Item>
                    <Space>
                      <Button type="primary" size="large">
                        提交审批意见
                      </Button>
                      <Button size="large">保存草稿</Button>
                    </Space>
                  </Form.Item>
                </Form>
              </Card>
            </Col>
            <Col xs={24} lg={8}>
              <Card title="审批提示">
                <Space direction="vertical" style={{ width: '100%' }}>
                  <Text type="secondary">• 请仔细核对申请信息和采购明细</Text>
                  <Text type="secondary">• 审批意见将发送给申请人和相关人员</Text>
                  <Text type="secondary">• 审批通过后将自动流转至下一审批节点</Text>
                  <Text type="secondary">• 驳回后申请人可修改后重新提交</Text>
                  <Text type="secondary">• 转审可将审批任务转交给其他人员</Text>
                </Space>
                <Divider />
                <Text strong>审批时限</Text>
                <br />
                <Text type="secondary" style={{ fontSize: 12 }}>请在 2026-01-03 前完成审批</Text>
              </Card>
            </Col>
          </Row>
        )}
      </Card>
    </div>
  );
};

export default FixHeader;
