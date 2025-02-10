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
    children: 'v1.0.3'
  },
  {
    key: '3',
    label: '最后更新时间',
    children: '2024-04-30'
  },
  {
    key: '6',
    label: 'Gitee',
    children: <Button type={'link'} href="https://gitee.com/xineny/xin-admin" size={'small'} target="_blank" >Gitee</Button>
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

const itemWeb: DescriptionsProps['items'] = [
  {
    key: '4',
    label: 'JS框架',
    children: '@umijs/max: ^4.1.10',
  },
  {
    key: '6',
    label: 'antd',
    children: 'antd: ^5.16.0'
  },
  {
    key: '1',
    label: 'Charts 图表',
    children: '@ant-design/charts: ^1.4.3',
  },
  {
    key: '2',
    label: 'Icon 图标',
    children: '@ant-design/icons: ^5.2.6',
  },
  {
    key: '3',
    label: '组件',
    children: '@ant-design/pro-components: ^2.7.0',
  },
  {
    key: '5',
    label: 'Hooks',
    children: 'ahooks: ^3.7.11',
  },
  {
    key: '8',
    label: '图片剪裁',
    children: 'antd-img-crop: ^4.21.0'
  },
  {
    key: '7',
    label: '日期库',
    children: 'dayjs: ^1.11.10'
  },
  {
    key: '9',
    label: '数据模拟',
    children: 'mockjs: ^1.1.0'
  }
];

const itemWebDev: DescriptionsProps['items'] = [
  {
    key: '1',
    label: 'types/mockjs',
    children: '@types/mockjs: ^1.0.7'
  },
  {
    key: '2',
    label: 'types/react',
    children: '@types/react: ^18.2.21'
  },
  {
    key: '3',
    label: 'types/react-dom',
    children: '@types/react-dom: ^18.2.7'
  },
  {
    key: '4',
    label: 'typescript',
    children: 'typescript: ^5.1.6'
  },
];

const itemPHP: DescriptionsProps['items'] = [
  {
    key: '1',
    label: 'php',
    children: 'php: >=8.1.0'
  },
  {
    key: '2',
    label: 'topthink/framework',
    children: 'topthink/framework: ^8.0'
  },
  {
    key: '3',
    label: 'topthink/think-orm',
    children: 'topthink/think-orm: ^3.0'
  },
  {
    key: '4',
    label: 'topthink/think-filesystem',
    children: 'topthink/think-filesystem: ^2.0'
  },
  {
    key: '5',
    label: 'topthink/think-multi-app',
    children: 'topthink/think-multi-app: ^1.0'
  },
  {
    key: '6',
    label: 'topthink/think-view',
    children: 'topthink/think-view: ^2.0'
  },
];


export default () => {


  return (
    <Card title={'系统信息'}>
      <Descriptions items={items} column={3} />
      <Row gutter={[16, 16]}>
        <Col span="12">
          <Space direction="vertical" size="middle" style={{ display: 'flex' }}>
            <Descriptions title="前端生产依赖" column={1} size={'small'} bordered items={itemWeb} />
            <Descriptions title="前端开发依赖" column={1} size={'small'} bordered items={itemWebDev} />
          </Space>
        </Col>
        <Col span="12">
          <Descriptions title="后端依赖" column={1} size={'small'} bordered items={itemPHP} />
        </Col>
      </Row>
    </Card>
  )
}
