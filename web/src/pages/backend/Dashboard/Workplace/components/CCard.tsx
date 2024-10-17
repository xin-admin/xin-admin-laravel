import { ProCard, StatisticCard } from '@ant-design/pro-components';
import type { TinyAreaConfig } from "@ant-design/charts";
import { TinyArea, TinyColumn, TinyColumnConfig } from '@ant-design/charts';
import { RingProgress } from "@/components/Charts";

const { Statistic } = StatisticCard;

export default () => {

  const data = [264, 417, 438, 887, 309, 397, 550, 575, 563, 430];
  const config: TinyAreaConfig = {
    height: 100,
    autoFit: true,
    padding: 0,
    data,
    smooth: true,
  };

  const data2 = [274, 337, 81, 497, 666, 219, 269];
  const config2: TinyColumnConfig = {
    height: 100,
    autoFit: true,
    data: data2,
    tooltip: {
      customContent: function (x, data) {
        return `NO.${x}: ${data[0]?.data2?.y.toFixed(2)}`;
      },
    },
    color: '#1890ff'
  };


  return (
    <ProCard split={'horizontal'}>
      <StatisticCard
        colSpan={6}
        title="财年业绩目标"
        statistic={{
          value: 82.6,
          suffix: '亿',
          description: <Statistic title="日同比" value="6.47%" trend="up" />,
        }}
        chart={
          <div style={{ display: 'flex', justifyContent: 'space-around' }}>
            <RingProgress size={80} title={'增长率'} percent={0.7098}></RingProgress>
            <RingProgress size={80} title={'完成率'} percent={0.8698}></RingProgress>
            <RingProgress size={80} title={'业绩'} percent={0.8898}></RingProgress>
          </div>
        }
        footer={
          <>
            <Statistic
              value="70.98%"
              title="财年业绩完成率"
              layout="horizontal"
            />
            <Statistic
              value="86.98%"
              title="去年同期业绩完成率"
              layout="horizontal"
            />
            <Statistic
              value="88.98%"
              title="前年同期业绩完成率"
              layout="horizontal"
            />
          </>
        }
      />

      <StatisticCard
        statistic={{
          title: '财年总收入',
          value: 601987768,
          description: (
            <Statistic title="日同比" value="6.15%" trend="up" />
          ),
        }}
        chart={<TinyColumn {...config2} />}
      >
        <Statistic
          title="大盘总收入"
          value={1982312}
          layout="vertical"
          description={
            <Statistic title="日同比" value="6.15%" trend="down" />
          }
        />
      </StatisticCard>
      <StatisticCard
        statistic={{
          title: '当日排名',
          value: 6,
          description: (
            <Statistic title="日同比" value="3.85%" trend="down" />
          ),
        }}
        chart={<TinyArea {...config} />}
      >
        <Statistic
          title="近7日收入"
          value={17458}
          layout="vertical"
          description={
            <Statistic title="日同比" value="6.47%" trend="up" />
          }
        />
      </StatisticCard>
      <StatisticCard
        statistic={{
          title: '财年业绩收入排名',
          value: 2,
          description: (
            <Statistic title="日同比" value="6.47%" trend="up" />
          ),
        }}
        chart={
          <TinyArea {...config} />
        }
      >
        <Statistic
          title="月付费个数"
          value={601}
          layout="vertical"
          description={
            <Statistic title="日同比" value="6.47%" trend="down" />
          }
        />
      </StatisticCard>
    </ProCard>

  );
};
