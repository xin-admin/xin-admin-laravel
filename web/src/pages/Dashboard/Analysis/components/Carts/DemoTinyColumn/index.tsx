import React from 'react';
import { TinyColumn } from '@ant-design/charts';
import type {TinyColumnConfig} from "@ant-design/charts";

const DemoTinyColumn = () => {
  const data = [274, 337, 81, 497, 666, 219, 269, 163, 159, 86, 15, 66];
  const config: TinyColumnConfig = {
    height: 60,
    autoFit: false,
    data,
    tooltip: {
      customContent: function (x, data) {
        return `NO.${x}: ${data[0]?.data?.y.toFixed(2)}`;
      },
    },
  };
  return <TinyColumn {...config} />;
};

export default DemoTinyColumn
