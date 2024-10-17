import { Button, Card, Space, DatePicker, Row, Col, List, Avatar, ConfigProvider } from "antd";
import DemoColumn from "../Carts/DemoColumn";
import { FormattedMessage } from "@umijs/max";
const { RangePicker } = DatePicker;
export default () => {

  const tableList = [
    {
      key: 'a',
      label: <FormattedMessage id={'analysis.tab1'} />,
    },
    {
      key: 'b',
      label: <FormattedMessage id={'analysis.tab2'} />,
    },
  ]

  const data = [
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
  ];
  const tabBarExtraContent = (
    <Space>
      <Button type={"text"}><FormattedMessage id={'analysis.button1'} /></Button>
      <Button type={"text"}><FormattedMessage id={'analysis.button2'} /></Button>
      <Button type={"text"}><FormattedMessage id={'analysis.button3'} /></Button>
      <Button type={"text"}><FormattedMessage id={'analysis.button4'} /></Button>
      <RangePicker />
    </Space>
  )

  return (
    <ConfigProvider
      theme={{
        components: {
          Card: {
            headerHeight: 100
          },
        },
      }}
    >
      <Card
        style={{ width: '100%' }}
        tabList={tableList}
        tabBarExtraContent={tabBarExtraContent}
      >
        <Row gutter={30}>
          <Col span={16}>
            <DemoColumn></DemoColumn>
          </Col>
          <Col span={8}>
            <FormattedMessage id={'analysis.title5'} />
            <List
              dataSource={data}
              renderItem={(item, index) => (
                <List.Item>
                  <List.Item.Meta
                    avatar={
                      <Avatar src={`https://xsgames.co/randomusers/avatar.php?g=pixel&key=${index}`} />
                    }
                    title={<a href="https://ant.design">{item.title}</a>}
                    description="Ant Design, a design language for background applications, is refined by Ant UED Team"
                  />
                </List.Item>
              )}
            />
          </Col>
        </Row>
      </Card>
    </ConfigProvider>

  )
}
