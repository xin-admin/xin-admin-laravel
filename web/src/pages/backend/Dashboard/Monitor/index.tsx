import {Card, Col, Row} from "antd";
import DemoMap from "./components/DemoMap";
import DemoCardOne from "./components/DemoCardOne";
import DemoCardTwo from "./components/DemoCardTwo";
import DemoCardThree from "./components/DemoCardThree";
import DemoCardFour from "./components/DemoCardFour";
import DemoCardFive from "./components/DemoCardFive";
import DemoCardSex from "./components/DemoCardSex";

export default () => {

  return (
    <Row gutter={24}>
      <Col span={18}>
        <Row gutter={[24,24]}>
          <Col span={24}>
            <Card
              title={'区域实时浏览情况'}
            >
              <DemoCardOne></DemoCardOne>
              <div style={{height: 600}}>
                <DemoMap></DemoMap>
              </div>
            </Card>
          </Col>
          <Col span={18}>
            <DemoCardFive></DemoCardFive>
          </Col>
          <Col span={6}>
            <DemoCardSex></DemoCardSex>
          </Col>
        </Row>
      </Col>
      <Col span={6}>
        <Row gutter={[24,24]}>
          <Col span={24}>
            <DemoCardTwo></DemoCardTwo>
          </Col>
          <Col span={24}>
            <DemoCardThree></DemoCardThree>
          </Col>
          <Col span={24}>
            <DemoCardFour></DemoCardFour>
          </Col>
        </Row>
      </Col>

    </Row>
  )
}
