import { StatisticCard } from '@ant-design/pro-components';
import { useState } from 'react';

const { Statistic, Divider } = StatisticCard;

export default () => {

  return (

    <StatisticCard.Group direction={'row'}>
      <StatisticCard
        statistic={{
          title: '总流量(人次)',
          value: 601986875,
        }}
      />
      <Divider type={'vertical'} />
      <StatisticCard
        statistic={{
          title: '付费流量',
          value: 3701928,
          description: <Statistic title="占比" value="61.5%" />,
        }}
        chart={
          <img
            src="https://gw.alipayobjects.com/zos/alicdn/ShNDpDTik/huan.svg"
            alt="百分比"
            width="100%"
          />
        }
        chartPlacement="left"
      />
      <StatisticCard
        statistic={{
          title: '免费流量',
          value: 1806062,
          description: <Statistic title="占比" value="38.5%" />,
        }}
        chart={
          <img
            src="https://gw.alipayobjects.com/zos/alicdn/6YR18tCxJ/huanlv.svg"
            alt="百分比"
            width="100%"
          />
        }
        chartPlacement="left"
      />
    </StatisticCard.Group>
  );
};
