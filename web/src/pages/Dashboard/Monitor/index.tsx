import { Card, Col, Row, Space } from 'antd';
import DemoMap from "./components/DemoMap";
import DemoCardOne from "./components/DemoCardOne";
import DemoCardTwo from "./components/DemoCardTwo";
import DemoCardThree from "./components/DemoCardThree";
import DemoCardFour from "./components/DemoCardFour";
import DemoCardFive from "./components/DemoCardFive";
import DemoCardSex from "./components/DemoCardSex";

export default () => {

  return (
    <Row gutter={20}>
      <Col span={18}>
        <Space direction={"vertical"} size={20}>
          <Card title={'区域实时浏览情况'}>
            <DemoCardOne></DemoCardOne>
            <div style={{height: 600}}>
              <DemoMap></DemoMap>
            </div>
          </Card>
          <DemoCardFive></DemoCardFive>
        </Space>
      </Col>
      <Col span={6}>
        <Space direction={"vertical"} size={20}>
          <DemoCardTwo></DemoCardTwo>
          <DemoCardThree></DemoCardThree>
          <DemoCardFour></DemoCardFour>
          <DemoCardSex></DemoCardSex>
        </Space>
      </Col>
    </Row>
  )
}
