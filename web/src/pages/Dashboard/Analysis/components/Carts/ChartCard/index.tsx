import {Tooltip,Statistic,ConfigProvider,Card} from 'antd';
import type { CardProps } from 'antd/es/card';
import React from 'react';

import {InfoCircleOutlined} from "@ant-design/icons";


export type ChartCardProps = {
  title: React.ReactNode;
  actionText?: string;
  total?:  number;
  footer?: React.ReactNode;
  style?: React.CSSProperties;
  precision?: number;
  prefix?: React.ReactNode;
  suffix?: React.ReactNode;
} & CardProps;

const ChartCard = (props: ChartCardProps) =>  {
  const {
    title,
    actionText,
    total,
    footer,
    precision,
    children,
    loading,
    prefix,
    suffix
  } = props;
  return (
    <Card loading={loading}>
      <ConfigProvider
        theme={{
          components: {
            Statistic: {
              contentFontSize: 30
            },
          },
        }}
      >
        <Statistic title={
          <div style={{display: 'flex',justifyContent: 'space-between'}}>
            <div>{title}</div>
            <Tooltip title={actionText}>
              <InfoCircleOutlined />
            </Tooltip>
          </div>
        } value={total} precision={precision} prefix={prefix}  suffix={suffix} />
        {children}
        <div style={{borderTop: '1px solid rgba(5, 5, 5, 0.06)',marginTop: '8px',paddingTop: '9px'}}>
          {footer}
        </div>
      </ConfigProvider>
    </Card>


  );

}

export default ChartCard;
