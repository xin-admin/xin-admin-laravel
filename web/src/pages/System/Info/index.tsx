import type { DescriptionsProps } from 'antd';
import { Button, Card, Col, Descriptions, Row, Space } from 'antd';

const items: DescriptionsProps['items'] = [
  {
    key: '1',
    label: '系统',
    children: 'Xin Admin',
  },
  {
    key: '2',
    label: '版本号',
    children: 'v1'
  },
  {
    key: '3',
    label: '最后更新时间',
    children: '2025-03-18'
  },
  {
    key: '6',
    label: 'Github',
    children: <Button type={'link'} href="https://github.com/xin-admin/xin-laravel" size={'small'} target="_blank" >Github</Button>
  },
  {
    key: '4',
    label: '官网地址',
    children: <Button type={'link'} href="https://xinadmin.cn" size={'small'} target="_blank" >https://xinadmin.cn</Button>
  },
  {
    key: '7',
    label: '小刘同学',
    children: <Button type={'link'} href="https://xineny.cn" size={'small'} target="_blank" >https://xineny.cn</Button>
  },
  {
    key: '5',
    label: '系统描述',
    children: 'Xin Admin 是一款开源快速构建全栈中后台应用的快速开发框架，包含丰富的特性，使得开发者在开发过程中如鱼得水，敏捷、快速、安全！'
  }
]

export default () => {


  return (
    <Card title={'系统信息'}>
      <Descriptions items={items} column={3} />
    </Card>
  )
}
