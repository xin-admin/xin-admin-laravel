import { CheckCard } from '@ant-design/pro-components';
import { Card } from 'antd';

export default () => (
  <Card  title={'单选卡片'}>
    <p>普通卡片</p>
    <CheckCard.Group
      size='small'
      options={['🍎 Apple', '🍐 Pear', '🍊 Orange']}
    />
    <br />
    <p>加载中</p>
    <CheckCard.Group
      size='small'
      loading
      options={['🍎 Apple', '🍐 Pear', '🍊 Orange']}
    />
    <br />
    <p>JSX风格</p>
    <CheckCard.Group defaultValue='A'>
      <CheckCard title='🍊 Orange' value='🍊 Orange' />
      <CheckCard title='🍐 Pear' value='🍐 Pear' />
      <CheckCard title='🍎 Apple' value='🍎 Apple' />
    </CheckCard.Group>
    <br />
    <p>加载中</p>
    <CheckCard.Group defaultValue='A' loading>
      <CheckCard title='🍊 Orange' value='🍊 Orange' />
      <CheckCard title='🍐 Pear' value='🍐 Pear' />
      <CheckCard title='🍎 Apple' value='🍎 Apple' />
    </CheckCard.Group>
  </Card>
);
