import { Button, Card, Space } from 'antd';
import React from 'react';
import { CardProps } from 'antd/es/card';
import { BetaSchemaForm, ProFormColumnsType } from '@ant-design/pro-components';
import { IGenSettingType } from '@/domain/iGenerator';
import { FormattedMessage } from '@umijs/max';

export default () => {

  // ----------- state -------------------
  const [tabChange, setTabChange] = React.useState('1');

  // tab 配置
  const tabList:  CardProps['tabList'] = [
    { key: '1', label: '基本配置' },
    { key: '2', label: '字段列表' },
    { key: '5', label: '数据库字段配置' },
    { key: '6', label: '表格字段配置' },
    { key: '3', label: '表单字段配置' },
    { key: '4', label: '搜索字段配置' }
  ]

  // -------------- Columns -------------------
  const baseSettingColumns: ProFormColumnsType<IGenSettingType>[] = [
    {
      dataIndex: 'name',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.tooltip.baseSetting.name'} />,
      tooltip: <FormattedMessage id={'gen.tooltip.baseSetting.name.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'path',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.tooltip.baseSetting.path'} />,
      tooltip: <FormattedMessage id={'gen.tooltip.baseSetting.path.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'module',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.tooltip.baseSetting.module'} />,
      tooltip: <FormattedMessage id={'gen.tooltip.baseSetting.module.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'routePrefix',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.tooltip.baseSetting.routePrefix'} />,
      tooltip: <FormattedMessage id={'gen.tooltip.baseSetting.routePrefix.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'abilitiesPrefix',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.tooltip.baseSetting.abilitiesPrefix'} />,
      tooltip: <FormattedMessage id={'gen.tooltip.baseSetting.abilitiesPrefix.tooltip'} />
    },
    {
      dataIndex: 'crudRequest',
      valueType: 'checkbox',
      valueEnum: {
        create: <FormattedMessage id={'gen.tooltip.baseSetting.crudRequest.create'} />,
        update: <FormattedMessage id={'gen.tooltip.baseSetting.crudRequest.update'} />,
        delete: <FormattedMessage id={'gen.tooltip.baseSetting.crudRequest.delete'} />,
        query: <FormattedMessage id={'gen.tooltip.baseSetting.crudRequest.query'} />,
        find: <FormattedMessage id={'gen.tooltip.baseSetting.crudRequest.find'} />
      },
      colProps: {span: 24},
      title: <FormattedMessage id={'gen.tooltip.baseSetting.crudRequest'} />,
      tooltip: <FormattedMessage id={'gen.tooltip.baseSetting.crudRequest.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'pagePath',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.tooltip.baseSetting.pagePath'} />,
      tooltip: <FormattedMessage id={'gen.tooltip.baseSetting.pagePath.tooltip'} />
    },
    {
      dataIndex: 'page_is_file',
      valueType: 'switch',
      colProps: {span: 8},
      title: <FormattedMessage id={'gen.tooltip.baseSetting.page_is_file'} />,
      tooltip: <FormattedMessage id={'gen.tooltip.baseSetting.page_is_file.tooltip'} />
    },
  ]

  // -------------- Element -------------------
  const tabBarExt = (
    <Space>
      <Button color="primary" variant="solid">新增字段</Button>
      <Button color="purple" variant="solid">AI一键生成字段</Button>
      <Button color="purple" variant="solid">导入数据库字段</Button>
      <Button color="magenta" variant="solid">生成预览</Button>
      <Button color="default" variant="solid">一键生成代码</Button>
    </Space>
  );

  const baseSettingForm = (
    <BetaSchemaForm<IGenSettingType>
      shouldUpdate={false}
      layoutType="Form"
      onFinish={async (values) => {
        console.log(values);
      }}
      initialValues={{
        page_is_file: false,
        crudRequest: ['create', 'update', 'delete', 'query', 'find']
      }}
      grid
      colProps={{ span: 12 }}
      rowProps={{ gutter: [40, 0] }}
      columns={baseSettingColumns}
      style={{width: 800}}
      submitter={{
        render: (props, doms) => {
          return [
            <Button type='default' key="rest" onClick={() => props.form?.resetFields()}>
              重置表单
            </Button>,
            <Button type="primary" key="preview" onClick={() => {

            }}>
              预览文件结果
            </Button>,
          ];
        },
      }}
    />
  );

  return (
    <Card
      tabBarExtraContent={tabBarExt}
      tabList={tabList}
      activeTabKey={tabChange}
      onTabChange={setTabChange}
      defaultActiveTabKey={'1'}
      loading={false}
      style={ { minWidth: 1080 }}
      styles={{ body: { minHeight: 500}
    }}
    >
      { tabChange === '1' && baseSettingForm }
    </Card>
  )
}
