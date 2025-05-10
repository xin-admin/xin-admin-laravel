import { Button, Card, message, Space } from 'antd';
import React, { useState } from 'react';
import { CardProps } from 'antd/es/card';
import {
  ActionType,
  BetaSchemaForm,
  EditableFormInstance,
  EditableProTable,
  ProColumns,
  ProFormColumnsType,
} from '@ant-design/pro-components';
import { IColumnsType, IDbColumnsType, IGenSettingType } from '@/domain/iGenerator';
import { FormattedMessage } from '@umijs/max';

// tab 配置
const tabList:  CardProps['tabList'] = [
  { key: '1', label: '基本配置' },
  { key: '2', label: '字段列表' },
  { key: '3', label: '数据库字段配置' },
  { key: '4', label: '表格字段配置' },
  { key: '5', label: '表单字段配置' },
  { key: '6', label: '搜索字段配置' }
]

export default () => {
  // -------------- state -------------------
  const [tabChange, setTabChange] = useState('1');
  const [baseColumns, setBaseColumns] = useState<IColumnsType[]>([]);
  const [baseColumnsEditableKeys, setBaseColumnsEditableKeys] = useState<React.Key[]>(() =>
    baseColumns.map((item) => item.id)
  );
  const [dbColumns, setDbColumns] = useState<IDbColumnsType[]>([]);
  const [dbColumnsEditableKeys, setDbColumnsEditableKeys] = useState<React.Key[]>(() =>
    dbColumns.map((item) => item.name)
  );

  // -------------- Ref -----------------------
  const baseColumnsTableRef = React.useRef<EditableFormInstance>();
  const baseColumnsTableActionRef = React.useRef<ActionType>();
  const dbColumnsTableRef = React.useRef<EditableFormInstance>();
  const dbColumnsTableActionRef = React.useRef<ActionType>();

  // -------------- Columns -------------------
  const baseSettingColumns: ProFormColumnsType<IGenSettingType>[] = [
    {
      dataIndex: 'name',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.name'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.name.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'path',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.path'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.path.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'module',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.module'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.module.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'routePrefix',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.routePrefix'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.routePrefix.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'abilitiesPrefix',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.abilitiesPrefix'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.abilitiesPrefix.tooltip'} />
    },
    {
      valueType: 'select',
      dataIndex: 'quickSearchField',
      title: <FormattedMessage id={'gen.baseSetting.quickSearchField'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.quickSearchField.tooltip'} />,
      params: {dbColumns: dbColumns},
      request: async (params: {dbColumns: IDbColumnsType[]}) => {
        return params.dbColumns.map(item => {
          return {
            label: item.name,
            value: item.name
          }
        })
      },
      fieldProps: {mode: "multiple"}
    },
    {
      dataIndex: 'crudRequest',
      valueType: 'checkbox',
      valueEnum: {
        create: <FormattedMessage id={'gen.baseSetting.crudRequest.create'} />,
        update: <FormattedMessage id={'gen.baseSetting.crudRequest.update'} />,
        delete: <FormattedMessage id={'gen.baseSetting.crudRequest.delete'} />,
        query: <FormattedMessage id={'gen.baseSetting.crudRequest.query'} />,
        find: <FormattedMessage id={'gen.baseSetting.crudRequest.find'} />
      },
      colProps: {span: 24},
      title: <FormattedMessage id={'gen.baseSetting.crudRequest'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.crudRequest.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'pagePath',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.pagePath'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.pagePath.tooltip'} />
    },
    {
      dataIndex: 'page_is_file',
      valueType: 'switch',
      colProps: {span: 8},
      title: <FormattedMessage id={'gen.baseSetting.page_is_file'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.page_is_file.tooltip'} />
    },
  ];
  const baseColumnColumns: ProColumns<IColumnsType>[] = [
    {
      dataIndex: 'id',
      valueType: 'text',
      editable: false,
      width: 140,
      title: <FormattedMessage id={'gen.baseColumn.id'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.id.tooltip'} />,
    },
    {
      dataIndex: 'name',
      valueType: 'text',
      editable: false,
      title: <FormattedMessage id={'gen.baseColumn.name'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.name.tooltip'} />,
    },
    {
      dataIndex: 'key',
      valueType: 'text',
      editable: false,
      title: <FormattedMessage id={'gen.baseColumn.key'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.key.tooltip'} />,
    },
    {
      dataIndex: 'remark',
      valueType: 'text',
      editable: false,
      title: <FormattedMessage id={'gen.baseColumn.remark'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.remark.tooltip'} />,
    },
    {
      dataIndex: 'dbColumns',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.baseColumn.dbColumns'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.dbColumns.tooltip'} />,
    },
    {
      dataIndex: 'table',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.baseColumn.table'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.table.tooltip'} />,
    },
    {
      dataIndex: 'form',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.baseColumn.form'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.form.tooltip'} />,
    },
    {
      dataIndex: 'select',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.baseColumn.select'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.select.tooltip'} />,
    },
    {
      valueType: 'option',
      width: 100,
      align: 'center',
      fixed: 'right',
      title: <FormattedMessage id={'gen.option'} />,
    },
  ];
  const addColumnColumns: ProFormColumnsType<IColumnsType>[] = [
    {
      dataIndex: 'name',
      valueType: 'text',
      title: <FormattedMessage id={'gen.baseColumn.name'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.name.tooltip'} />,
    },
    {
      dataIndex: 'remark',
      valueType: 'text',
      title: <FormattedMessage id={'gen.baseColumn.remark'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.remark.tooltip'} />,
    },
    {
      dataIndex: 'key',
      valueType: 'text',
      title: <FormattedMessage id={'gen.baseColumn.key'} />,
      tooltip: <FormattedMessage id={'gen.baseColumn.key.tooltip'} />,
    },
  ];
  const dbColumnColumns: ProColumns<IDbColumnsType>[] = [
    {
      dataIndex: 'name',
      valueType: 'text',
      title: <FormattedMessage id={'gen.dbColumn.name'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.name.tooltip'} />,
    },
    {
      dataIndex: 'type',
      valueType: 'text',
      title: <FormattedMessage id={'gen.dbColumn.type'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.type.tooltip'} />,
    },
    {
      dataIndex: 'comment',
      valueType: 'text',
      title: <FormattedMessage id={'gen.dbColumn.comment'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.comment.tooltip'} />,
    },
    {
      dataIndex: 'default',
      valueType: 'text',
      title: <FormattedMessage id={'gen.dbColumn.default'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.default.tooltip'} />,
    },
    {
      dataIndex: 'length',
      valueType: 'digit',
      title: <FormattedMessage id={'gen.dbColumn.length'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.length.tooltip'} />,
    },
    {
      dataIndex: 'notNull',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.dbColumn.notNull'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.notNull.tooltip'} />,
    },
    {
      dataIndex: 'unsigned',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.dbColumn.unsigned'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.unsigned.tooltip'} />,
    },
    {
      dataIndex: 'autoincrement',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.dbColumn.autoincrement'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.autoincrement.tooltip'} />,
    },
    {
      dataIndex: 'precision',
      valueType: 'digit',
      title: <FormattedMessage id={'gen.dbColumn.precision'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.precision.tooltip'} />,
    },
    {
      dataIndex: 'scale',
      valueType: 'digit',
      title: <FormattedMessage id={'gen.dbColumn.scale'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.scale.tooltip'} />,
    },
    {
      dataIndex: 'presetValues',
      valueType: 'text',
      title: <FormattedMessage id={'gen.dbColumn.presetValues'} />,
      tooltip: <FormattedMessage id={'gen.dbColumn.presetValues.tooltip'} />,
    }
  ]

  // -------------- Function -------------------
  const addBaseColumn = async (values: IColumnsType) => {
    if(baseColumns.find((item) => item.key === values.key)) {
      message.error('字段已存在');
      return false;
    }
    return baseColumnsTableActionRef.current?.addEditRecord({
      ...values,
      select: false,
      form: false,
      table: false,
      dbColumns: false,
      id: Date.now().toString(),
    }, { newRecordType: 'dataSource' });
  }

  // -------------- Element -------------------
  const tabBarExt = (<>
    <Space>
      <BetaSchemaForm<IColumnsType>
        trigger={<Button color="primary" variant="solid">新增字段</Button>}
        shouldUpdate={false}
        layoutType="ModalForm"
        onFinish={addBaseColumn}
        columns={addColumnColumns}
      />
      <Button color="purple" variant="solid">AI一键生成字段</Button>
      <Button color="purple" variant="solid">导入数据库字段</Button>
      <Button color="magenta" variant="solid">生成预览</Button>
      <Button color="default" variant="solid">一键生成代码</Button>
    </Space>
  </>);
  const baseSettingForm = (<>
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
      onValuesChange={(data, allValues) => {
        console.log(allValues);
      }}
      submitter={{
        render: (props) => {
          return [
            <Button type='default' key="rest" onClick={() => props.form?.resetFields()}>
              重置表单
            </Button>,
            <Button type="primary" key="preview" onClick={() => {}}>
              预览文件结果
            </Button>,
          ];
        },
      }}
    />
  </>);
  const baseColumnsTable = (<>
    <EditableProTable<IColumnsType>
      columns={baseColumnColumns}
      rowKey="id"
      size={'large'}
      bordered={true}
      scroll={{ x: 800 }}
      value={baseColumns}
      toolBarRender={false}
      recordCreatorProps={false}
      editableFormRef={baseColumnsTableRef}
      actionRef={baseColumnsTableActionRef}
      editable={{
        type: 'multiple',
        editableKeys: baseColumnsEditableKeys,
        actionRender: (row, config, defaultDoms) => {
          return [defaultDoms.delete];
        },
        onValuesChange: (record, recordList) => {
          console.log(record);
          setBaseColumns(recordList);
        },
        onChange: setBaseColumnsEditableKeys,
      }}
    />
  </>);
  const dbColumnsTable = (<>
    <EditableProTable<IDbColumnsType>
      columns={dbColumnColumns}
      rowKey="name"
      size={'large'}
      bordered={true}
      scroll={{ x: 800 }}
      value={dbColumns}
      toolBarRender={false}
      recordCreatorProps={false}
      editableFormRef={dbColumnsTableRef}
      actionRef={dbColumnsTableActionRef}
      editable={{
        type: 'multiple',
        editableKeys: dbColumnsEditableKeys,
        actionRender: (row, config, defaultDoms) => {
          return [defaultDoms.delete];
        },
        onValuesChange: (record, recordList) => {
          console.log(record);
          setDbColumns(recordList);
        },
        onChange: setBaseColumnsEditableKeys,
      }}
    />
  </>);

  return (<>
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
      { tabChange === '2' && baseColumnsTable }
      { tabChange === '3' && dbColumnsTable }
    </Card>
  </>)
}
