import React, { useState, useEffect } from 'react';
import { AreaMap } from '@ant-design/charts';
import jsonData from './index.json';
const DemoAreaMap = () => {
  const [data, setData] = useState<any>({ type: 'FeatureCollection', features: [] });

  useEffect(() => {
    let featuresData = jsonData.features.map((item: { properties: any; }) => {
      return {
        ...item,
        properties: {
          ...item.properties,
          price: (Math.random() * 100000).toFixed(2)
        }
      }
    })
    setData({...data, features: featuresData})
  }, []);


  const color = [
    '#6395fa',
    '#62daab'
  ];
  const config = {
    map: {
      type: 'mapbox',
      style: 'blank',
      center: [120.19382669582967, 30.258134],
      zoom: 3,
      pitch: 0,
    },
    source: {
      data: data,
      parser: {
        type: 'geojson',
      },
    },
    autoFit: true,
    color: {
      field: 'price',
      value: color,
      scale: {
        type: 'quantile',
      },
    },
    style: {
      opacity: 1,
      stroke: 'rgb(93,112,146)',
      lineType: 'dash',
      lineDash: [2, 2],
      lineWidth: 0.6,
      lineOpacity: 1,
    },
    state: {
      active: true,
      select: true,
    },
    tooltip: {
      items: ['name', 'price'],
    },
    zoom: {
      position: 'bottomright',
    },
    legend: {
      position: 'bottomleft',
    },
  };

  // @ts-ignore
  return <AreaMap {...config} />;
};

export default DemoAreaMap
