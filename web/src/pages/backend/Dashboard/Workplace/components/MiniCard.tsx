import {Row, Col, Divider} from 'antd';
import {TinyArea, TinyColumn} from '@ant-design/charts';
import type {TinyAreaConfig, TinyColumnConfig} from '@ant-design/charts';
import {RingProgress, WordCloud, Column} from '@/components/Charts';
import { ProCard } from '@ant-design/pro-components';
import { Statistic } from 'antd';

const HomePage: React.FC = () => {
  const data = [
    264, 417, 438, 887, 309, 397, 550, 575, 563, 430,
  ];
  const config: TinyAreaConfig = {
    height: 100,
    autoFit: false,
    padding: 0,
    data,
    smooth: true,
    areaStyle: {
      fill: '#1890ff'
    },
    color: '#1890ff'
  };
  const data2 = [274, 337, 81, 497, 666, 219, 269];
  const config2: TinyColumnConfig = {
    height: 100,
    autoFit: false,
    data: data2,
    tooltip: {
      customContent: function (x, data) {
        return `NO.${x}: ${data[0]?.data2?.y.toFixed(2)}`;
      },
    },
    color: '#1890ff'
  };

  const cardStyle = {width: '100%', height: '160px'}
  return (
    <Row gutter={[16,16]}>
      <Col span={24}>
        <ProCard.Group title="核心指标" direction={'row'} >
          <ProCard>
            <Statistic title="今日UV" value={79.0} precision={2} />
          </ProCard>
          <Divider type={'vertical'} />
          <ProCard>
            <Statistic title="冻结金额" value={112893.0} precision={2} />
          </ProCard>
          <Divider type={'vertical'} />
          <ProCard>
            <Statistic title="信息完整度" value={93} suffix="/ 100" />
          </ProCard>
          <Divider type={'vertical'} />
          <ProCard>
            <Statistic title="冻结金额" value={112893.0} />
          </ProCard>
        </ProCard.Group>
      </Col>
      <Col span={6}>
        <ProCard  style={cardStyle} size={'small'} title={'用户增长趋势'}><TinyArea {...config}/></ProCard>
      </Col>
      <Col span={6}>
        <ProCard  style={cardStyle} size={'small'} title={'用户来源'}>
          <div style={{ display: 'flex',justifyContent: 'space-around' }}>
            <RingProgress size={ 90 } title={'百度'} percent={ 0.8 }></RingProgress>
            <RingProgress size={ 90 } title={'谷歌'} percent={ 0.4 }></RingProgress>
            <RingProgress size={ 90 } title={'阿里'} percent={ 0.2 }></RingProgress>
          </div>
        </ProCard>
      </Col>
      <Col span={6}>
        <ProCard  style={cardStyle} title={'业绩记录'} size={'small'} >
          <TinyColumn {...config2}/>
        </ProCard>
      </Col>
      <Col span={6}>
        <ProCard  style={cardStyle} title={'提成记录'} size={'small'} >
          <TinyColumn {...config2}/>
        </ProCard>
      </Col>
      <Col span={12}>
        <ProCard  style={{width: '100%', height: '600px' }} size={'small'} title={'Ant Design 词图'}>
          <div style={{width: '100%',height: '100%', display: 'flex',justifyContent: 'space-around',alignContent: 'center'}}>
            <WordCloud size={500}/>
          </div>
        </ProCard>
      </Col>
      <Col span={12}>
        <ProCard  style={{width: '100%', height: '600px' }} size={'small'} title={'行业热度'}>
          <div style={{width: '100%',height: '100%'}}>
            <Column/>
          </div>
        </ProCard>
      </Col>
    </Row>
  );
};

export default HomePage;
