import ChartCard from './components/Carts/ChartCard';
import { StatisticCard } from '@ant-design/pro-components';
import { Col, Row } from 'antd';
import DemoTinyArea from './components/Carts/DemoTinyArea';
import DemoTinyColumn from './components/Carts/DemoTinyColumn';
import DemoProgress from './components/Carts/DemoProgress';
import DemoCardOne from './components/DemoCardOne';
import DemoCardTwo from './components/DemoCardTwo';
import DemoCardThree from './components/DemoCardThree';
import { FormattedMessage } from '@@/exports';

const { Statistic } = StatisticCard;
export default () => {
  return (
    <Row gutter={[24, 24]}>
      <Col xs={20} sm={16} md={12} lg={8} xl={6}>
        <ChartCard

          title={<FormattedMessage id={'analysis.title1'} />}
          actionText={'Tips'}
            loading={false}
            total={126560}
            suffix={<FormattedMessage id={'analysis.symbol1'}/>}
            footer={<><FormattedMessage id={'analysis.bottom1'} />￥12,423</>}
          >
            <div style={{height: 60,display: 'flex',flexDirection: 'column-reverse'}}>
              <Statistic title={<FormattedMessage id={'analysis.dayRatio'}/>} value="8.63%" trend="down" />
              <Statistic title={<FormattedMessage id={'analysis.weekRatio'}/>} value="6.47%" trend="up" />
            </div>
          </ChartCard>
        </Col>
        <Col xs={20} sm={16} md={12} lg={8} xl={6}>
          <ChartCard

            title={<FormattedMessage id={'analysis.title2'}/>}
            actionText={'总访问量'}
            loading={false}
            total={8860}
            suffix={'Ips'}
            footer={<><FormattedMessage id={'analysis.bottom2'}/>1,345</>}
          >
            <DemoTinyArea></DemoTinyArea>
          </ChartCard>
        </Col>
        <Col xs={20} sm={16} md={12} lg={8} xl={6}>
          <ChartCard

            title={<FormattedMessage id={'analysis.title3'}/>}
            actionText={'支付笔数'}
            loading={false}
            total={3608}
            suffix={<FormattedMessage id={'analysis.symbol2'}/>}
            footer={<><FormattedMessage id={'analysis.bottom3'}/>60%</>}
          >
            <DemoTinyColumn></DemoTinyColumn>
          </ChartCard>
        </Col>
        <Col xs={20} sm={16} md={12} lg={8} xl={6}>
          <ChartCard

            title={<FormattedMessage id={'analysis.title4'}/>}
            actionText={'运营活动效果'}
            loading={false}
            total={78}
            suffix={'%'}
            footer={<Statistic title={<><FormattedMessage id={'analysis.weekRatio'}/></>} value="8.63%" trend="down" />}
          >
            <DemoProgress></DemoProgress>
          </ChartCard>
        </Col>
        <Col span={24}>
          <DemoCardOne></DemoCardOne>
        </Col>
        <Col span={12}>
          <DemoCardTwo></DemoCardTwo>
        </Col>
        <Col span={12}>
          <DemoCardThree></DemoCardThree>
        </Col>
      </Row>
  );
};
