import { ProCard, StatisticCard } from '@ant-design/pro-components';

const { Statistic } = StatisticCard;

export default () => {

  return (
    <ProCard
      title="数据概览"
      extra="2019年9月28日 星期五"
      split={'vertical'}
      headerBordered
    >
      <ProCard split="horizontal">
        <ProCard split="horizontal">
          <ProCard split="vertical">
            <StatisticCard
              statistic={{
                title: '昨日全部流量',
                value: 234,
                description: (
                  <Statistic
                    title="较本月平均流量"
                    value="8.04%"
                    trend="down"
                  />
                ),
              }}
            />
            <StatisticCard
              statistic={{
                title: '本月累计流量',
                value: 234,
                description: (
                  <Statistic title="月同比" value="8.04%" trend="up" />
                ),
              }}
            />
          </ProCard>
          <ProCard split="vertical">
            <StatisticCard
              statistic={{
                title: '运行中实验',
                value: '12/56',
                suffix: '个',
              }}
            />
            <StatisticCard
              statistic={{
                title: '历史实验总数',
                value: '134',
                suffix: '个',
              }}
            />
          </ProCard>
        </ProCard>
        <StatisticCard
          title="流量走势"
          chart={
            <img
              src="https://gw.alipayobjects.com/zos/alicdn/_dZIob2NB/zhuzhuangtu.svg"
              width="100%"
            />
          }
        />
      </ProCard>
      <StatisticCard
        title="流量占用情况"
        chart={
          <img
            src="https://gw.alipayobjects.com/zos/alicdn/qoYmFMxWY/jieping2021-03-29%252520xiawu4.32.34.png"
            alt="大盘"
            width="100%"
          />
        }
      />
    </ProCard>
  );
};