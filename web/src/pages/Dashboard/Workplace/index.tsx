import { Col, Row, Space } from 'antd';
import BCard from './components/BCard';
import ACard from "./components/ACard";
import CCard from "./components/CCard";
import { ProCard } from '@ant-design/pro-components';


const HomePage = () => {

  return (
    <Row gutter={20}>
      <Col span={18}>
        <Space direction={"vertical"} size={20}>
          <ACard></ACard>
          <BCard></BCard>
        </Space>
      </Col>
      <Col span={6}>
        <CCard></CCard>
      </Col>
    </Row>
  );
};

export default HomePage;
