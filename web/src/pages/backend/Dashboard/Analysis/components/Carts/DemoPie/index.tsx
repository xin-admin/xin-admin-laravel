import React from 'react';
import { Pie } from '@ant-design/charts';

const DemoPie = () => {
  const data = [
    {
      type: '家用电器',
      value: 27,
    },
    {
      type: '食用酒水',
      value: 25,
    },
    {
      type: '个护健康',
      value: 18,
    },
    {
      type: '服饰箱包',
      value: 15,
    },
    {
      type: '母婴产品',
      value: 10,
    },
    {
      type: '其他',
      value: 5,
    },
  ];
  const config = {
    appendPadding: 10,
    data,
    angleField: 'value',
    colorField: 'type',
    radius: 0.75,
    label: {
      type: 'spider',
      labelHeight: 28,
      content: '{name}\n{percentage}',
    },
    interactions: [
      {
        type: 'element-selected',
      },
      {
        type: 'element-active',
      },
    ],
  };
  return <Pie {...config} />;
};

export default DemoPie
