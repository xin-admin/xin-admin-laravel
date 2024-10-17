import { RingProgress } from '@ant-design/charts';


const DemoRingProgress:React.FC<ChartProps.RingProgressProps> = (props) => {
  const {
    size ,
    title = '进度',
    color = '#1890ff',
    percent = 0.6
  } = props;
  const config = {
    height: size,
    width: size,
    autoFit: false,
    percent: percent,
    color: [color, '#E8EDF3'],
    innerRadius: 0.85,
    radius: 0.98,
    statistic: {
      title: {
        style: {
          color: '#363636',
          fontSize: '12px',
          lineHeight: '14px',
        },
        formatter: () => title,
      },
    },
  };
  return (
    <div style={{ width: size,height: size }}>
      <RingProgress {...config} />
    </div>
  );
};

export default DemoRingProgress;
