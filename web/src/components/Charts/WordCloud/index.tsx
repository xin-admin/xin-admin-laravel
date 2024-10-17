import React, { useState, useEffect } from 'react';
import {WordCloud, WordCloudConfig} from '@ant-design/charts';

const DemoWordCloud: React.FC<ChartProps.WordCloudProps> = (props) => {
  const { size } = props;
  const [data, setData] = useState([]);

  const asyncFetch = () => {
    fetch('https://gw.alipayobjects.com/os/antvdemo/assets/data/antv-keywords.json')
      .then((response) => response.json())
      .then((json) => setData(json))
      .catch((error) => {
        console.log('fetch data failed', error);
      });
  };

  useEffect(() => {
    asyncFetch();
  }, []);


  const config: WordCloudConfig = {
    data,
    wordField: 'name',
    weightField: 'value',
    colorField: 'name',
    wordStyle: {
      fontFamily: 'Verdana',
      fontSize: [8, 32],
      rotation: 0,
    },
    // 返回值设置成一个 [0, 1) 区间内的值，
    // 可以让每次渲染的位置相同（前提是每次的宽高一致）。
    random: () => 0.5,
  };


  return (
    <div style={{ height:size,width:size}}>
      <WordCloud {...config} />
    </div>
  );
};

export default DemoWordCloud;
