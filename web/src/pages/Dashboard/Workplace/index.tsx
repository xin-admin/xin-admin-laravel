import {Col, Row} from 'antd';
import BCard from './components/BCard';
import ACard from "./components/ACard";
import CCard from "./components/CCard";
import { ProCard } from '@ant-design/pro-components';


const HomePage = () => {

  return (
    <Row gutter={[24,24]}>
      <Col span={18}>
        <Row gutter={[24,24]}>
          <Col span={24}>
            <ProCard bordered>
              <ACard></ACard>
            </ProCard>
          </Col>
          <Col span={24}>
            <ProCard bordered>
              <BCard></BCard>
            </ProCard>
          </Col>
        </Row>
      </Col>
      <Col span={6}>
        <ProCard bordered>
          <CCard></CCard>
        </ProCard>
      </Col>
    </Row>
  );
};

export default HomePage;
