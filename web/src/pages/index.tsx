import React, { useEffect, useRef } from 'react';
import './index.less';
import { Button, Carousel, Col, ConfigProvider, Divider, Row, Space, Statistic, Typography } from 'antd';
import { GridContent, ProCard } from '@ant-design/pro-components';

const { Text } = Typography;
const Index: React.FC = () => {
  const videoElement = useRef<HTMLVideoElement>(null);
  const logos = [
    'https://main.qcloudimg.com/raw/2044066d0a02578e788df97b6e5d1ced.png',
    'https://main.qcloudimg.com/raw/820db8ee8476add3083b49eeed19c972.png',
    'https://main.qcloudimg.com/raw/0dee802275e6ecf9f5f3ccd543df6565.png',
    'https://main.qcloudimg.com/raw/957872087501c288403585e43e0d0ad6.png',
    'https://qcloudimg.tencent-cloud.cn/raw/4e2ba47c6ce08c7296e76101cd976595.png',
    'https://main.qcloudimg.com/raw/af4fc1ecbf7bc1aa5577f36a43fea746.png',
    'https://qcloudimg.tencent-cloud.cn/raw/e4d108b0c6ff6ab05e832a634dc5a0a4.png',
    'https://qcloudimg.tencent-cloud.cn/raw/da5a1d91c95ad81b9ad7dc87983cd218.png',
    'https://qcloudimg.tencent-cloud.cn/raw/f218313ddad35eeb29e4669cf86602e9.png',
    'https://qcloudimg.tencent-cloud.cn/raw/ff9228b8a10e682de65d634062500993.png',
    'https://qcloudimg.tencent-cloud.cn/raw/3a5fb166b6e9d3be5ca90ed3f8475c8c.png',
    'https://sponsors.vuejs.org/images/herodevs.png',
    'https://sponsors.vuejs.org/images/ionic.png?v2',
    'https://sponsors.vuejs.org/images/storyblok.png',
    'https://sponsors.vuejs.org/images/passionate_people.png',
    'https://sponsors.vuejs.org/images/vuemastery.png',
    'https://sponsors.vuejs.org/images/vehikl.png',
    'https://sponsors.vuejs.org/images/vueschool.png',
    'https://sponsors.vuejs.org/images/learnvue.png',
    'https://sponsors.vuejs.org/images/localazy.svg',
    'https://sponsors.vuejs.org/images/enkrypt__ethereum_and_polkadot_web3_wallet.svg',
    'https://sponsors.vuejs.org/images/devexpress.png',
    'https://sponsors.vuejs.org/images/fastcoding_inc.svg',
    'https://sponsors.vuejs.org/images/intygrate.png',
    'https://sponsors.vuejs.org/images/tidelift.png',
    'https://sponsors.vuejs.org/images/laravel.png',
    'https://sponsors.vuejs.org/images/volta.svg',
  ]

  const carouselData = [
    { name: 'Xin Admin', tip: '全栈开发框架' },
    { name: 'And Design', tip: '全球几十万开发者都在用的 UI 框架' },
    { name: 'Think PHP', tip: '国内优秀的 PHP 框架' }
  ]


  useEffect(() => {
    if (videoElement.current) {
      videoElement.current.play().then()
    }
  }, [videoElement])

  return (
    <ConfigProvider
      theme={{
        token: {
          borderRadius: 0,
          controlHeight: 46
        },
      }}
    >
      <GridContent>
        <Space direction="vertical" size="large" style={{ display: 'flex' }}>
          <Carousel className={'banner'} autoplay>
            {carouselData.map((item) => (
              <div className={'banner-list'} key={item.name}>
                <h1>{item.name}</h1>
                <h2>{item.tip}</h2>
                <Button block type="primary">立即使用</Button>
              </div>
            ))}
          </Carousel>

          <ProCard.Group direction={'row'} title={
            <>
              <Button type={'primary'} onClick={() => location.href = '/admin/login'}>登录管理后台</Button>
            </>
          }>
            <ProCard>
              <Statistic title="强大的前端组件驱动。便捷的权限验证，crud表格，动态菜单，约定式路由等，只需一个 Columns 就可以实现增删改查等表单、表格、查询等功能，以及组件的高度自定义，搭配完善的数据字典系统，轻松的构建你的业务系统。" value={'前沿技术栈驱动🌺'} precision={2} />
            </ProCard>
            <Divider type={'vertical'} />
            <ProCard>
              <Statistic title="便捷且易用的 CRUD 开发，编辑字段立即看到表格效果，支持多种组件演示，支持下拉框、单选、多选等。编辑即可看到表单表格以及查询等效果。支持前后端双重数据验证，支持自定义验证。极大的方便了开发者。" value={'可视化 CRUD🧩'} precision={2} />
            </ProCard>
            <Divider type={'vertical'} />
            <ProCard>
              <Statistic title="我们基于 antd v5 cssinjs 动态主题配置与暗色主题算法封装了，为应用级场景提供易用的亮暗色主题切换能力，使用方式更加简单。Ant Design Style 将为响应式应用提供便捷的工具函数，帮助开发者快速完成响应式主题开发。" value={'响应式轻松适配✨'} />
            </ProCard>
            <Divider type={'vertical'} />
            <ProCard>
              <Statistic title="完善的权限管理体系，页面、菜单、按钮三级权限控制，支持无限父子级权限分组，支持部门权限分派控制，支持数据权限（精细化数据权限控制，控制到行级，列表级，表单字段级，实现不同人看不同数据，不同人对同一个页面操作不同字段" value={'权限控制系统♻️'} />
            </ProCard>
          </ProCard.Group>

          <h1 style={{ textAlign: 'center', marginTop: 20 }}>
            全方位的解决方案
          </h1>

          <Row justify="space-around">
            <Col>
              <img src={'https://file.xinadmin.cn/png/%E6%9D%A5%E7%94%B5%E5%90%8D%E7%89%87.png'} width={100} alt={'文件处理'}></img>
              <div style={{ width: '100%', textAlign: 'center', marginTop: 10 }}>文件处理</div>
            </Col>
            <Col>
              <img src={'https://file.xinadmin.cn/png/%E8%90%A5%E9%94%80%E5%BD%A9%E4%BF%A1.png'} width={100} alt={'身份验证'}></img>
              <div style={{ width: '100%', textAlign: 'center', marginTop: 10 }}>身份验证</div>
            </Col >
            <Col>
              <img src={'https://file.xinadmin.cn/png/%E8%A7%86%E9%A2%91%E7%9F%AD%E4%BF%A1.png'} width={100} alt={'云开发'}></img>
              <div style={{ width: '100%', textAlign: 'center', marginTop: 10 }}>云开发</div>
            </Col>
            <Col>
              <img src={'https://file.xinadmin.cn/png/%E9%97%AA%E9%AA%8C.png'} width={100} alt={'数据安全'}></img>
              <div style={{ width: '100%', textAlign: 'center', marginTop: 10 }}>数据安全</div>
            </Col>
            <Col>
              <img src={'https://file.xinadmin.cn/png/%E5%A4%A7%E6%95%B0%E6%8D%AE%E7%B2%BE%E5%87%86%E8%90%A5%E9%94%80.png'} width={100} alt={'数据安全'}></img>
              <div style={{ width: '100%', textAlign: 'center', marginTop: 10 }}>大数据精准</div>
            </Col>
          </Row>

          <Row style={{ width: '100%', background: '#fff', borderRadius: 10, padding: 20, paddingBottom: 40 }}>
            <Col xs={24} sm={24} md={12} lg={12} xl={12} style={{ paddingTop: '50px', paddingLeft: '50px' }}>
              <h1 style={{ marginBottom: 30 }}>致力于打造满足严苛要求的云产品</h1>
              <Text disabled>亚太合规资质最全的云服务商之一，从基础设施安全、内核平台安全、系统服务安全、云安全产品四个层面，保障千行百业客户的业务安全在线。拥有权威认可的原生安全能力，根据2021年Gartner报告，安全能力全球第一。</Text>
              <p></p>
              <Text disabled>自2009年创立之初，阿里云就提出“云计算，让计算成为公共服务”，并坚持通过云的弹性和自服务能力支持企业敏捷创新。自2016年起，阿里云持续保持中国第一、亚太第一、全球第三的市场地位。当前阿里云服务超400万客户，包括60%A股上市公司。</Text>
              <p></p>
              <Text disabled>飞天是阿里云自主研发、国内唯一自研的云计算操作系统，编排调度百万级服务器，单集群调度规模超十万台，具备EB级数据存储能力，并通过CIPU率先实现虚拟化“0”损耗，提供业界领先的计算性能，既满足客户严苛的业务要求，又提供高性价比服务。</Text>
              <p></p>
              <Text disabled>阿里云为全球30个地域、89个可用区的客户提供稳定性全球领先的产品技术。弹性计算单实例可用性SLA高达99.975%，数据存储设计可靠性高达12个9，提供稳如磐石的客户体验。</Text>
              <p></p>
              <Text disabled>以上文案来自阿里云</Text>
            </Col>
            <Col xs={24} sm={24} md={12} lg={12} xl={12} style={{ textAlign: 'center' }}>
              <img src='https://file.xinadmin.cn/png/%E9%97%AE%E5%8D%B7%E8%B0%83%E6%9F%A5.svg' alt='' style={{ height: '100%', minHeight: 300 }} />
            </Col>
          </Row>

          <div style={{ textAlign: 'center', marginTop: 20 }}>
            <h1>服务全球海量客户的行业案例</h1>
            <p> 腾讯云完善的服务体系为企业数字化上云保驾护航 <Button type={'link'}>查看更多客户案例</Button></p>
          </div>
          <Row style={{ width: '100%', background: '#fff', padding: 40, borderRadius: 10 }}>
            <Col span={24}>
              <Row justify="space-evenly" className={'logo-group'}>
                {logos.map((src, index) => {
                  return (
                    <Col className={'logos'} key={index}>
                      <div style={{ backgroundImage: 'url(' + src + ')' }} className={'logo'}>
                      </div>
                    </Col>
                  )
                })}
              </Row>
            </Col>
          </Row>
        </Space>
      </GridContent>

    </ConfigProvider>
  );
};


export default Index;
