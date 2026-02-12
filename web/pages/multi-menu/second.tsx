import {Button, Card, Row, Col, Breadcrumb, Space} from "antd";

const Second = () => (
  <div>
    <Card style={{ marginBottom: 16 }}>
      <Breadcrumb items={[
        { title: '多级菜单' },
        { title: '二级菜单' },
        { title: '三级页面' },
      ]} />
      <h2 style={{ marginTop: 16, marginBottom: 0 }}>三级页面</h2>
    </Card>
    <Row gutter={[16, 16]}>
      <Col span={24}>
        <Card style={{height: 200}} />
      </Col>
      <Col span={16}>
        <Card style={{height: 200}} />
      </Col>
      <Col span={8}>
        <Card style={{height: 200}} />
      </Col>
    </Row>
    <Card style={{ marginTop: 16 }}>
      <Space>
        <Button>重置</Button>
        <Button type="primary">提交</Button>
      </Space>
    </Card>
  </div>
);

export default Second;
