import React from "react";
import {Card, Col, Row, theme, Radio, Table, Tag, Space, List, Avatar} from "antd";
import ReactECharts from "echarts-for-react";
import {ArrowDownOutlined, ArrowUpOutlined, LikeOutlined, TeamOutlined} from "@ant-design/icons";
import { useTranslation } from 'react-i18next';
const {useToken} = theme;

interface DataType {
  key: string;
  name: string;
  age: number;
  address: string;
  tags: string[];
}

const Index: React.FC = () => {
  const {token} = useToken();
  const { t } = useTranslation();

  const optionsBar = {
    xAxis: {
      type: 'category',
      show: false,
      data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']
    },
    yAxis: {
      show: false,
      type: 'value'
    },
    series: [
      {
        data: [120, 88, 116, 60, 70],
        type: 'bar',
        itemStyle: {
          color: token.colorPrimary
        },
        barWidth: 10,
      }
    ]
  }

  const optionsLine = {
    xAxis: {
      show: false,
      type: 'category',
      data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    },
    yAxis: {
      show: false,
      type: 'value'
    },
    series: [
      {
        data: [1, 2, 3, 2, 3, 2, 1],
        type: 'line',
        itemStyle: {
          color: token.colorPrimary
        }
      }
    ]
  };

  const options = {
    title: {
      text: t('dashboard.analysis.annualSales'),
      textStyle: {
        color: token.colorText,
        fontSize: token.fontSizeLG,
        fontWeight: token.fontWeightStrong
      },
    },
    tooltip: {
      trigger: 'axis',
      axisPointer: {
        type: 'cross',
        label: {
          backgroundColor: token.colorPrimaryBg,
          color: token.colorPrimary
        }
      },
      borderWidth: 0,
      backgroundColor: token.colorPrimaryBg,
      textStyle: {
        color: token.colorText
      }
    },
    legend: {
      data: [t('dashboard.analysis.grossProfit'), t('dashboard.analysis.netProfit'), t('dashboard.analysis.totalExpense')],
      textStyle: {
        color: token.colorText
      }
    },
    grid: {
      left: '3%',
      right: '4%',
      bottom: '3%',
      containLabel: true
    },
    xAxis: [
      {
        type: 'category',
        boundaryGap: false,
        data: [
          t('dashboard.analysis.january'),
          t('dashboard.analysis.february'),
          t('dashboard.analysis.march'),
          t('dashboard.analysis.april'),
          t('dashboard.analysis.may'),
          t('dashboard.analysis.june'),
          t('dashboard.analysis.july'),
          t('dashboard.analysis.august'),
          t('dashboard.analysis.september'),
          t('dashboard.analysis.october'),
          t('dashboard.analysis.november'),
          t('dashboard.analysis.december')
        ]
      }
    ],
    yAxis: [
      {
        type: 'value',
        splitLine: {
          lineStyle: {
            // 使用深浅的间隔色
            color: token.colorBorder
          }
        }
      },
    ],
    series: [
      {
        name: t('dashboard.analysis.grossProfit'),
        type: 'line',
        stack: 'Total',
        areaStyle: {
          color: token.colorPrimaryBorder
        },
        emphasis: {
          focus: 'series'
        },
        itemStyle: {
          color: token.colorPrimary
        },
        data: [30,36,42,33,21,26,29,35,42,32,28,26]
      },
      {
        name: t('dashboard.analysis.netProfit'),
        type: 'line',
        stack: 'Total',
        areaStyle: {
          color: token.colorSuccessBorder
        },
        emphasis: {
          focus: 'series'
        },
        itemStyle: {
          color: token.colorSuccess
        },
        data: [32,16,18,30,15,19,22,17,24,19,30,31]
      },
      {
        name: t('dashboard.analysis.totalExpense'),
        type: 'line',
        stack: 'Total',
        areaStyle: {
          color: token.colorWarningBorder
        },
        emphasis: {
          focus: 'series'
        },
        itemStyle: {
          color: token.colorWarning
        },
        data: [36,24,36,36,39,56,24,23,21,12,16,19]
      }
    ]
  }

  const optionsPie = {
    title: {
      text: t('dashboard.analysis.accessFrom'),
      textStyle: {
        color: token.colorText,
        fontSize: token.fontSizeLG,
        fontWeight: token.fontWeightStrong
      },
    },
    tooltip: {
      trigger: 'item'
    },
    legend: {
      bottom: '0%',
      left: 'center',
      textStyle: {
        color: token.colorText
      }
    },
    series: [
      {
        name: t('dashboard.analysis.accessFrom'),
        type: 'pie',
        radius: ['40%', '70%'],
        avoidLabelOverlap: false,
        itemStyle: {
          borderRadius: token.borderRadius,
          borderColor: token.colorBorder,
          borderWidth: 2
        },
        label: {
          show: false,
          position: 'center'
        },
        emphasis: {
          label: {
            show: true,
            fontSize: 40,
            fontWeight: 'bold'
          }
        },
        labelLine: {
          show: false
        },
        data: [
          { value: 1048, name: t('dashboard.analysis.searchEngine') },
          { value: 735, name: t('dashboard.analysis.direct') },
          { value: 580, name: t('dashboard.analysis.email') },
          { value: 484, name: t('dashboard.analysis.unionAds') },
          { value: 300, name: t('dashboard.analysis.videoAds') }
        ]
      }
    ]
  };

  const data: DataType[] = [
    {
      key: '1',
      name: 'John Brown',
      age: 32,
      address: 'New York No. 1 Lake Park',
      tags: ['nice', 'developer'],
    },
    {
      key: '2',
      name: 'Jim Green',
      age: 42,
      address: 'London No. 1 Lake Park',
      tags: ['loser'],
    },
    {
      key: '3',
      name: 'Joe Black',
      age: 32,
      address: 'Sydney No. 1 Lake Park',
      tags: ['cool', 'teacher'],
    },
    {
      key: '4',
      name: 'Jim Green',
      age: 42,
      address: 'London No. 1 Lake Park',
      tags: ['loser'],
    },
    {
      key: '5',
      name: 'Joe Black',
      age: 32,
      address: 'Sydney No. 1 Lake Park',
      tags: ['cool', 'teacher'],
    },
  ];

  const msgData = [
    {
      title: 'Ant Design Title 1',
    },
    {
      title: 'Ant Design Title 2',
    },
    {
      title: 'Ant Design Title 3',
    },
    {
      title: 'Ant Design Title 4',
    },
    {
      title: 'Ant Design Title 5',
    },
  ];

  return (
    <>
      <Row gutter={[20, 20]}>
        <Col xxl={6} lg={12} xs={24}>
          <Card variant={"borderless"}>
            <div>{t('dashboard.analysis.totalRevenue')}</div>
            <div className={"flex items-center justify-between pt-4 pb-2"}>
              <div className={"text-4xl flex-0"}>￥3,415.00</div>
              <ReactECharts style={{width: 120, height: 80}} option={optionsBar} />
            </div>
            <div>{t('dashboard.since.lastWeek')} <span style={{color: token.colorError}}><ArrowUpOutlined />11.28%</span></div>
          </Card>
        </Col>
        <Col xxl={6} lg={12} xs={24}>
          <Card variant={"borderless"}>
            <div>{t('dashboard.analysis.totalExpenses')}</div>
            <div className={"flex items-center justify-between pt-4 pb-2"}>
              <div className={"text-4xl flex-0"}>￥8,425.00</div>
              <ReactECharts style={{width: 120, height: 80}} option={optionsLine} />
            </div>
            <div>{t('dashboard.since.lastWeek')} <span style={{color: token.colorSuccess}}><ArrowDownOutlined />15.33%</span></div>
          </Card>
        </Col>
        <Col xxl={6} lg={12} xs={24}>
          <Card variant={"borderless"}>
            <div>{t('dashboard.analysis.visitors')}</div>
            <div className={"flex items-center justify-between pt-4 pb-2"}>
              <div className={"text-4xl flex-0"}>
                1,128
              </div>
              <div className={"text-4xl rounded-full flex items-center justify-center"} style={{height: 80, width: 80, background: token.colorPrimaryBg}}>
                <TeamOutlined style={{ color: token.colorPrimary }}/>
              </div>
            </div>
            <div>{t('dashboard.since.lastWeek')} <span style={{color: token.colorError}}><ArrowUpOutlined />32.60%</span></div>
          </Card>
        </Col>
        <Col xxl={6} lg={12} xs={24}>
          <Card variant={"borderless"}>
            <div>{t('dashboard.analysis.likes')}</div>
            <div className={"flex items-center justify-between pt-4 pb-2"}>
              <div className={"text-4xl flex-0"}>
                668
              </div>
              <div className={"text-4xl rounded-full flex items-center justify-center"} style={{height: 80, width: 80, background: token.colorPrimaryBg}}>
                <LikeOutlined  style={{ color: token.colorPrimary }}/>
              </div>
            </div>
            <div>{t('dashboard.since.lastWeek')} <span style={{color: token.colorSuccess}}><ArrowDownOutlined />9.60%</span></div>
          </Card>
        </Col>
        <Col xl={18} xs={24}>
          <Card variant={"borderless"}>
            <ReactECharts style={{width: '100%', height: 460}} option={options} />
          </Card>
        </Col>
        <Col xl={6} xs={24}>
          <Card variant={"borderless"}>
            <ReactECharts style={{width: '100%', height: 460}} option={optionsPie} />
          </Card>
        </Col>
        <Col xl={12} xs={24}>
          <Card variant={"borderless"}>
            <div className={"flex items-center justify-between mb-5"}>
              <div style={{ fontSize: token.fontSizeLG, fontWeight: token.fontWeightStrong }}>{t('dashboard.analysis.salesRanking')}</div>
              <Radio.Group
                options={[
                  { label: t('dashboard.analysis.month'), value: 'month'},
                  { label: t('dashboard.analysis.year'), value: 'year'},
                  { label: t('dashboard.analysis.day'), value: 'day'},
                ]}
                defaultValue={'day'}
                optionType="button"
                buttonStyle="solid"
              />
            </div>
            <Table
              columns={[
                {
                  title: t('dashboard.analysis.article'),
                  dataIndex: 'name',
                  key: 'name',
                  render: (text) => <a>{text}</a>,
                },
                {
                  title: t('dashboard.analysis.age'),
                  dataIndex: 'age',
                  key: 'age',
                },
                {
                  title: t('dashboard.analysis.address'),
                  dataIndex: 'address',
                  key: 'address',
                },
                {
                  title: t('dashboard.analysis.tags'),
                  key: 'tags',
                  dataIndex: 'tags',
                  render: (_, { tags }) => (
                    <>
                      {tags.map((tag) => {
                        let color = tag.length > 5 ? 'geekblue' : 'green';
                        if (tag === 'loser') {
                          color = 'volcano';
                        }
                        return (
                          <Tag color={color} key={tag}>
                            {tag.toUpperCase()}
                          </Tag>
                        );
                      })}
                    </>
                  ),
                },
                {
                  title: t('dashboard.analysis.action'),
                  key: 'action',
                  render: (_, record) => (
                    <Space size="middle">
                      <a>{t('dashboard.analysis.invite')} {record.name}</a>
                      <a>{t('dashboard.analysis.delete')}</a>
                    </Space>
                  ),
                },
              ]}
              dataSource={data}
              pagination={false}
              scroll={{x: 800}}
            />
          </Card>
        </Col>
        <Col xl={12} xs={24}>
          <Card variant={"borderless"}>
            <div className={"mb-5"} style={{ fontSize: token.fontSizeLG, fontWeight: token.fontWeightStrong }}>{t('dashboard.analysis.userReviews')}</div>
            <List
              dataSource={msgData}
              renderItem={(item, index) => (
                <List.Item>
                  <List.Item.Meta
                    avatar={
                      <Avatar src={`https://xsgames.co/randomusers/avatar.php?g=pixel&key=${index}`} />
                    }
                    title={<a href="https://ant.design">{item.title}</a>}
                    description={t('dashboard.analysis.reviewDescription')}
                  />
                </List.Item>
              )}
            />
          </Card>
        </Col>
      </Row>
    </>
  )
}

export default Index