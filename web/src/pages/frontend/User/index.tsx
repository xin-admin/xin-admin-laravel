import { StatisticCard } from '@ant-design/pro-components';
import UserLayout from './components/UserLayout';
import { Card } from 'antd';

const { Divider } = StatisticCard;

export default () => {
  return (
    <UserLayout>
      <Card>
        <StatisticCard.Group title={<h3>我的代办</h3>}>
          <StatisticCard
            statistic={{
              title: '全部',
              tip: '帮助文字',
              value: 10,
            }}
          />
          <Divider />
          <StatisticCard
            statistic={{
              title: '已处理消息',
              value: 5,
              status: 'default',
            }}
          />
          <StatisticCard
            statistic={{
              title: '待处理消息',
              value: 3,
              status: 'processing',
            }}
          />
          <StatisticCard
            statistic={{
              title: '警告消息',
              value: 2,
              status: 'error',
            }}
          />
          <StatisticCard
            statistic={{
              title: '系统通知',
              value: '-',
              status: 'success',
            }}
          />
        </StatisticCard.Group>
      </Card>
    </UserLayout>
  );
};
