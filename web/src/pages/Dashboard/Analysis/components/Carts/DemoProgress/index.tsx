import React from 'react';
import { Progress } from '@ant-design/charts';

const DemoProgress = () => {
  const config = {
    height: 60,
    autoFit: false,
    percent: 0.7,
    color: ['#5B8FF9', '#E8EDF3'],
  };
  return <Progress {...config} />;
};

export default DemoProgress
