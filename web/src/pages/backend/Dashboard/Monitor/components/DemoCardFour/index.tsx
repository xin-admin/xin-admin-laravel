import React from 'react';
import { Liquid } from '@ant-design/charts';
import {Card} from "antd";

const DemoLiquid = () => {
  const config = {
    percent: 0.25,
    height: 200,
    outline: {
      border: 4,
      distance: 8,
    },
    wave: {
      length: 128,
    },
  };
  return (
    <Card title={'水波图'} >
      <Liquid {...config} />
    </Card>
  );
};

export default DemoLiquid
