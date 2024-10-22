import { useModel, useNavigate, useSearchParams } from '@umijs/max';
import React, { useRef, useState } from 'react';
import { Button, Card, Col, ConfigProvider, message, Row, Space, Tabs, Typography } from 'antd';
import { crudApi } from '@/services/admin/online';
import { useAsyncEffect } from 'ahooks';
import { editApi, listApi } from '@/services/common/table';
import {
  BetaSchemaForm,
  EditableProTable,
  ProColumns,
  ProDescriptions,
  ProFormColumnsType,
  ProFormInstance,
  useDebounceFn,
} from '@ant-design/pro-components';
import { defaultCRUDConfig, defaultSql, defaultTableConfig, defaultTableSetting } from './components/defaultData';

export default () => {
  const nav = useNavigate();
  const [searchParams] = useSearchParams();
  const { dictEnum } = useModel('dictModel');
  const [tabChange, setTableChange] = useState('1');

  // CRUD 配置
  const [tableConfig, setTableConfig] = useState<OnlineType.OnlineTableType>(defaultTableConfig);
  // 表格配置 表单Ref
  const tableSettingForm = useRef<ProFormInstance>()
  // CRUD 表单REF
  const crudFormRef = useRef<ProFormInstance>()
  const [editableKeys, setEditableRowKeys] = useState<React.Key[]>();
  const isMenu = ['select', 'checkbox', 'radio', 'radioButton'];

  // 字段配置
  const columnsFormColumns: ProColumns<OnlineType.ColumnsConfig>[] = [
    {
      title: '基本配置',
      children: [
        {
          title: '字段名',
          dataIndex: 'dataIndex',
          tooltip: '作为数据库字段名和列索引',
          valueType: 'text',
          formItemProps: {
            rules: [
              { required: true, message: '此项为必填项' },
            ],
          },
          width: 120,
          align: 'center',
          fixed: 'left',
        },
        {
          title: '表单类型',
          dataIndex: 'valueType',
          valueType: 'select',
          align: 'center',
          tooltip: '生成CRUD表单的类型',
          valueEnum: dictEnum.get('valueType'),
          fieldProps: (form, { rowKey }) => {
            return {
              onChange: (value: any) => {
                if (defaultSql.hasOwnProperty(value)) {
                  form.setFieldValue(rowKey, {
                    ...defaultSql[value],
                    ...form.getFieldValue(rowKey),
                  });
                }
              },
            };
          },
          formItemProps: {
            rules: [
              { required: true, message: '此项为必填项' },
            ],
          },
          width: 120,
          fixed: 'left',
        },
        {
          title: '字段备注',
          dataIndex: 'title',
          valueType: 'text',
          tooltip: '作为表格表头和表单项名称',
          width: 120,
          align: 'center',
          formItemProps: {
            rules: [
              { required: true, message: '此项为必填项' },
            ],
          },
          fixed: 'left',
        },
      ],
    },
    {
      title: '生成设置',
      children: [
        {
          title: '查询方式',
          align: 'center',
          dataIndex: 'select',
          valueEnum: dictEnum.get('select'),
          valueType: 'text',
          width: 120,
        },
        {
          title: '验证规则',
          dataIndex: 'validation',
          valueType: 'select',
          valueEnum: dictEnum.get('validation'),
          align: 'center',
          fieldProps: { mode: 'multiple' },
          tooltip: '内置部分验证规则，需要自定义验证规则请看文档',
          width: 120,
        },
        {
          title: '搜索禁用',
          dataIndex: 'hideInSearch',
          valueType: 'switch',
          width: 80,
          align: 'center',
        },
        {
          title: '表格禁用',
          dataIndex: 'hideInTable',
          valueType: 'switch',
          align: 'center',
          width: 80,
        },
        {
          title: '表单禁用',
          dataIndex: 'hideInForm',
          valueType: 'switch',
          align: 'center',
          width: 80,
        },
        {
          title: '数据枚举',
          dataIndex: 'enum',
          valueType: 'textarea',
          tooltip: 'key:label 格式，以换行分割',
          formItemProps: {
            rules: [
              { required: true, message: '此项为必填项' },
            ],
          },
          width: 140,
          align: 'center',
          fieldProps: (form, { rowKey }) => {
            let valueType = form?.getFieldValue([rowKey, 'valueType']);
            let isDict = form?.getFieldValue([rowKey, 'isDict']);
            if (isMenu.includes(valueType) && !isDict) {
              return { disabled: false, autoSize: true };
            }
            return { disabled: true, autoSize: true };
          },
        },
      ],
    },
    {
      title: '字段类型',
      dataIndex: 'sqlType',
      valueType: 'text',
      tooltip: '请输入正确的数据库类型！',
      align: 'center',
      valueEnum: dictEnum.get('sqlType'),
      width: 120,
    },
    {
      title: '数据库配置',
      children: [
        {
          title: '字段长度',
          dataIndex: 'sqlLength',
          valueType: 'text',
          align: 'center',
          width: 100,
          fieldProps: (form, { rowKey }) => {
            let sqlType: string = form?.getFieldValue([rowKey, 'sqlType']);
            console.log(sqlType);
            if (sqlType && !['varchar', 'decimal', 'enum'].includes(sqlType)) {
              return { disabled: true, value: '' };
            }
            return { disabled: false };
          },
        },
        {
          title: '字段默认值',
          dataIndex: 'defaultValue',
          valueType: 'text',
          tooltip: '支持 null（NULL） 和 empty string（EMPTY STRING） 以及其它非空字符串',
          align: 'center',
          width: 120,
          fieldProps: (form, { rowKey }) => {
            let sqlType: string = form?.getFieldValue([rowKey, 'sqlType']);
            if (sqlType && sqlType === 'text') {
              return { disabled: true, checked: false };
            }
            return { disabled: false };
          },
        },
        {
          title: '主键',
          dataIndex: 'isKey',
          valueType: 'switch',
          tooltip: '主键只能有一个',
          width: 80,
          align: 'center',
          fieldProps: (form, { rowKey }) => {
            let sqlType: string = form?.getFieldValue([rowKey, 'sqlType']);
            if (sqlType && sqlType === 'text') {
              return { disabled: true, checked: false };
            }
            return { disabled: false };
          },
        },
        {
          title: '不为空',
          dataIndex: 'null',
          valueType: 'switch',
          width: 60,
          align: 'center',
          fieldProps: (form, { rowKey }) => {
            let sqlType: string = form?.getFieldValue([rowKey, 'sqlType']);
            if (sqlType && sqlType === 'text') {
              return { disabled: true, checked: false };
            }
            return { disabled: false };
          },
        },
        {
          title: '递增',
          dataIndex: 'autoIncrement',
          valueType: 'switch',
          width: 60,
          align: 'center',
          fieldProps: (form, { rowKey }) => {
            let sqlType: string = form?.getFieldValue([rowKey, 'sqlType']);
            if (sqlType && sqlType === 'int') {
              return { disabled: false };
            }
            return { disabled: true, checked: false };
          },
        },
      ],
    },
    {
      title: '操作',
      valueType: 'option',
      width: 80,
      align: 'center',
      fixed: 'right',
    },
  ];
  // 表格配置
  const tableSettingColumns: ProFormColumnsType<OnlineType.TableConfig>[] = [
    {
      valueType: 'text',
      renderFormItem: () => (
        <Typography.Title level={5} style={{ margin: 0 }}>表格设置</Typography.Title>
      )
    },
    {
      title: '表格标题',
      valueType: 'text',
      dataIndex: 'headerTitle',
    },
    {
      title: '表格提示',
      valueType: 'text',
      dataIndex: 'tooltip',
    },
    {
      title: '表格尺寸',
      valueType: 'radio',
      dataIndex: 'size',
      valueEnum: new Map([
        ['default', '大'],
        ['middle', '中'],
        ['small', '小'],
      ])
    },
    {
      valueType: 'text',
      renderFormItem: () => (
        <Typography.Title level={5} style={{ margin: 0 }}>功能开关</Typography.Title>
      )
    },
    {
      title: '表格多选',
      valueType: 'switch',
      dataIndex: 'rowSelectionShow',
      colProps: { span: 8 },
    },
    {
      title: '表格新增',
      valueType: 'switch',
      dataIndex: 'addShow',
      colProps: { span: 8 }
    },
    {
      title: '表格删除',
      valueType: 'switch',
      dataIndex: 'deleteShow',
      colProps: { span: 8 }
    },
    {
      title: '表格编辑',
      valueType: 'switch',
      dataIndex: 'editShow',
      colProps: { span: 8 }
    },
    {
      title: '表格边框',
      valueType: 'switch',
      dataIndex: 'bordered',
      colProps: { span: 8 }
    },
    {
      title: '显示标题',
      valueType: 'switch',
      dataIndex: 'showHeader',
      colProps: { span: 8 }
    },
    {
      valueType: 'text',
      renderFormItem: () => (
        <Typography.Title level={5} style={{ margin: 0 }}>查询配置</Typography.Title>
      )
    },
    {
      title: '表格查询',
      valueType: 'switch',
      dataIndex: 'searchShow',
      colProps: { span: 8 }
    },
    {
      valueType: 'dependency',
      name: ['searchShow'],
      columns: ({ searchShow }) => {
        if (searchShow === false) return []
        return [
          {
            title: '重置按钮文案',
            valueType: 'text',
            dataIndex: ['search', 'resetText'],
          },
          {
            title: '查询按钮文案',
            valueType: 'text',
            dataIndex: ['search', 'searchText'],
          },
          {
            title: '表单栅格',
            valueType: 'radio',
            dataIndex: ['search', 'span'],
            valueEnum: new Map([
              [24, 24],
              [12, 12],
              [8, 8],
              [6, 6],
            ]),
          },
          {
            title: '表单布局',
            valueType: 'radioButton',
            dataIndex: ['search', 'layout'],
            fieldProps: {
              size: 'small'
            },
            valueEnum: new Map([
              ['vertical', '垂直'],
              ['horizontal', '水平']
            ]),
            colProps: { span: 12 },
          },
          {
            title: '表单类型',
            valueType: 'radioButton',
            dataIndex: ['search', 'filterType'],
            valueEnum: new Map([
              ['query', '默认'],
              ['light', '轻量']
            ]),
            fieldProps: {
              size: 'small'
            },
            colProps: { span: 12 },
          },
        ]
      }
    },
    {
      valueType: 'text',
      renderFormItem: () => (
        <Typography.Title level={5} style={{ margin: 0 }}>操作栏配置</Typography.Title>
      )
    },
    {
      title: '启用状态',
      valueType: 'switch',
      dataIndex: 'optionsShow',
      colProps: { span: 8 },
    },
    {
      valueType: 'dependency',
      name: ['optionsShow'],
      columns: ({ optionsShow }) => {
        if (optionsShow === false) return []
        return [
          {
            title: '刷新按钮',
            valueType: 'switch',
            dataIndex: ['options', 'reload'],
            colProps: { span: 8 },
          },
          {
            title: '密度按钮',
            valueType: 'switch',
            dataIndex: ['options', 'density'],
            colProps: { span: 8 },
          },
          {
            title: '一键搜索',
            valueType: 'switch',
            dataIndex: ['options', 'search'],
            colProps: { span: 8 },
          },
          {
            title: '全屏按钮',
            valueType: 'switch',
            dataIndex: ['options', 'fullScreen'],
            colProps: { span: 8 },
          },
          {
            title: '列设置',
            valueType: 'switch',
            dataIndex: ['options', 'setting'],
            colProps: { span: 8 },
          },
        ]
      }
    },
    {
      valueType: 'text',
      renderFormItem: () => (
        <Typography.Title level={5} style={{ margin: 0 }}>分页配置</Typography.Title>
      )
    },
    {
      title: '启用状态',
      valueType: 'switch',
      dataIndex: 'paginationShow',
      colProps: { span: 12 },
    },
    {
      valueType: 'dependency',
      name: ['paginationShow'],
      columns: ({ paginationShow }) => {
        if (paginationShow === false) return []
        return [
          {
            title: '分页尺寸',
            valueType: 'radioButton',
            dataIndex: ['pagination', 'size'],
            valueEnum: new Map([
              ['default', '默认'],
              ['small', '小'],
            ]),
            fieldProps: { size: 'small' },
            colProps: { span: 12 },
          },
          {
            title: '简介分页',
            valueType: 'switch',
            dataIndex: ['pagination', 'simple'],
            colProps: { span: 12 },
          },
        ]
      }
    },
  ];
  // CRUD 配置
  const crudColumns: ProFormColumnsType<OnlineType.CrudConfig>[] = [
    {
      title: '数据表名称',
      dataIndex: 'sqlTableName',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入数据表名称',
      },
      tooltip: '必填,不带数据表前缀，生成自动添加'
    },
    {
      title: '数据库备注',
      dataIndex: 'sqlTableRemark',
      valueType: 'text',
    },
    {
      title: '生成文件名',
      dataIndex: 'name',
      valueType: 'text',
      tooltip: '请使用大驼峰命名'
    },
    {
      title: '控制器目录',
      dataIndex: 'controllerPath',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入路径',
        addonBefore: 'app/admin/controller/',
      },
      tooltip: '将生成（app/admin/controller/ + 输入路径 + 文件名 + Controller.php）文件， 如果在控制器根目录则省略不填'
    },
    {
      title: '模型目录',
      dataIndex: 'modelPath',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入模型路径',
        addonBefore: 'app/admin/model/',
      },
      tooltip: '将生成（app/admin/model/ + 输入路径 + 文件名 + Model.php）文件， 如果在模型根目录则省略不填'
    },
    {
      title: '验证器目录',
      dataIndex: 'validatePath',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入验证器路径',
        addonBefore: 'app/admin/validate/',
      },
      tooltip: '将生成（app/admin/validate/ + 输入路径 + 文件名 + .php）文件， 如果在验证器根目录则省略不填'
    },
    {
      title: '前端页面目录',
      dataIndex: 'pagePath',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入前端页面目录',
        addonBefore: 'src/pages/backend/',
      },
      tooltip: '将生成（web/admin/src/pages/backend/ + 输入路径 + 文件夹（文件名） + .tsx）文件， 如果在前端页面根目录则省略不填，如果自行迁移前端项目路径请在 .env 文件中修改路径'
    },
    {
      valueType: 'switch',
      title: '开启软删除',
      dataIndex: 'autoDeletetime',
      fieldProps: {
        style: {
          width: '200px',
        },
      },
    },
  ];

  // 初始化
  useAsyncEffect(async () => {
    const deviseId = searchParams.get('id');
    if (!deviseId) { nav('/online/table', { replace: true }); return;}
    let resData = await listApi('/online/online_table/list', { id: deviseId });
    let onlineTableData: any;
    if (Array.isArray(resData.data.data) && resData.data.data.length > 0) {
      onlineTableData = resData.data.data[0];
    } else {
      nav('/online/table', { replace: true });
      return;
    }
    let columns: OnlineType.ColumnsConfig[];
    let table_config: OnlineType.TableConfig;
    let crud_config: OnlineType.CrudConfig;
    if (
      typeof onlineTableData.columns === 'string' &&
      typeof onlineTableData.table_config === 'string' &&
      typeof onlineTableData.crud_config === 'string'
    ) {
      try {
        columns = JSON.parse(onlineTableData.columns);
        table_config = JSON.parse(onlineTableData.table_config);
        crud_config = JSON.parse(onlineTableData.crud_config);
        setTableConfig({
          id: deviseId,
          columns: columns,
          tableSetting: table_config,
          crudConfig: crud_config,
        });
        tableSettingForm.current?.setFieldsValue(table_config)
        setEditableRowKeys(columns.map((i: any) => i.key))
        console.log(crud_config);
        crudFormRef.current?.setFieldsValue(crud_config)
      } catch (e) {
        message.warning('数据不是有效 JSON');
        console.log(e);
      }
    } else {
      message.warning('数据不是有效 JSON 字符串');
    }
  }, []);

  // 保存数据
  const saveOnlineTable = async () => {
    let data = {
      id: tableConfig.id,
      columns: JSON.stringify(tableConfig.columns),
      table_config: JSON.stringify(tableConfig.tableSetting),
      crud_config: JSON.stringify(tableConfig.crudConfig),
    };
    await editApi('/online/online_table/edit', data)
    message.success('保存成功！');
  };

  // 保存并生成代码
  const crud = async () => {
    await saveOnlineTable();
    let tableSetting: OnlineType.TableConfig = { ...tableConfig.tableSetting };
    delete tableSetting.paginationShow;
    delete tableSetting.searchShow;
    delete tableSetting.optionsShow;
    let data = {
      id: tableConfig.id,
      columns: tableConfig.columns,
      table_config: tableSetting,
      crud_config: tableConfig.crudConfig,
    };
    await crudApi(data)
    message.success('代码生成成功！');
  };

  // 编辑表格设置 去抖配置
  const updateTableSettingConfig = useDebounceFn(async (state: OnlineType.TableConfig) => {
    if (!state.searchShow) state.search = false
    if (!state.optionsShow) state.options = false
    if (!state.paginationShow) state.pagination = false
    setTableConfig({
      ...tableConfig,
      tableSetting: state
    })
  }, 300);

  // 编辑字段 去抖配置
  const updateColumnsConfig = useDebounceFn(async (state) => {
    setTableConfig({ ...tableConfig, columns: state })
  }, 300);

  // 编辑字段 去抖配置
  const updateCRUDConfig = useDebounceFn(async (state) => {
    setTableConfig({ ...tableConfig, crudConfig: state })
  }, 300);

  // 添加字段
  const createColumns = (createData: OnlineType.ColumnsConfig) => {
    let arr: OnlineType.ColumnsConfig[] = [
      ...tableConfig.columns,
      {
        key: createData.dataIndex! + Date.now().toString(),
        valueType: createData.valueType,
        title: createData.title! + tableConfig.columns.length,
        dataIndex: createData.dataIndex! + tableConfig.columns.length,
        select: createData.select,
        validation: createData.validation,
        hideInForm: createData.hideInForm,
        hideInSearch: createData.hideInSearch,
        hideInTable: createData.hideInTable,
        enum: createData.enum,
        defaultValue: createData.defaultValue,
        isKey: createData.isKey,
        null: createData.null,
        autoIncrement: createData.autoIncrement,
        unsign: createData.unsign,
        mock: createData.mock,
        sqlLength: createData.sqlLength,
        sqlType: createData.sqlType
      }
    ]
    setTableConfig({ ...tableConfig, columns: arr })
    setEditableRowKeys(arr.map((item: any) => item.key))
  }

  const tabItem = [
    {
      key: '1',
      label: '表格配置',
      children: (
        <Row style={{ maxWidth: 1200 }}>
          <Col span={16}>
            <ConfigProvider theme={{
              components: {
                Form: {inlineItemMarginBottom: 8}
              }
            }}>
              <BetaSchemaForm<OnlineType.TableConfig>
                layout="inline"
                layoutType={'Form'}
                formRef={tableSettingForm}
                grid={true}
                onValuesChange={(_, values) => updateTableSettingConfig.run(values)}
                initialValues={defaultTableSetting}
                columns={tableSettingColumns}
                submitter={{ render: () => [] }}
              />
            </ConfigProvider>
          </Col>
          <Col span={8}>
            <ProDescriptions title={'配置JSON'}>
              <ProDescriptions.Item valueType="jsonCode" style={{ width: '100%' }}>
                {JSON.stringify(tableConfig.tableSetting)}
              </ProDescriptions.Item>
            </ProDescriptions>
          </Col>
        </Row>
      )
    },
    {
      key: '2',
      label: '字段配置',
      children: (
        <div>
          <Space style={{ marginBottom: 10 }}>
            {defaultSql.map((item) => (
              <Button type="dashed" key={item.title} onClick={() => createColumns(item)}>{item.title}</Button>
            ))}
          </Space>
          <EditableProTable
            columns={columnsFormColumns}
            rowKey="key"
            scroll={{ x: 1600 }}
            value={tableConfig.columns}
            bordered
            recordCreatorProps={false}
            size={'middle'}
            editable={{
              type: 'multiple',
              editableKeys,
              actionRender: (row, config, defaultDom) => {
                return [defaultDom.delete];
              },
              onValuesChange: (record, recordList) => {
                updateColumnsConfig.run(recordList).then(() => {
                });
              },
            }}
          />
        </div>
      )
    },
    {
      key: '3',
      label: '生成配置',
      children: (
        <BetaSchemaForm<OnlineType.CrudConfig>
          onValuesChange={(record, recordList) => {
            updateCRUDConfig.run(recordList).then(() => {});
          }}
          layoutType={'Form'}
          layout={'horizontal'}
          colProps={{ span: 12 }}
          labelCol={{ span: 6 }}
          grid={true}
          initialValues={{...defaultCRUDConfig, ...tableConfig.crudConfig}}
          formRef={crudFormRef}
          columns={crudColumns}
          submitter={{ render: () => <></> }}
        />
      )
    },
  ];

  return (
    <Card>
      <Tabs
        items={tabItem}
        defaultActiveKey={'1'}
        onChange={(key) => {
          setTableChange(key);
        }}
        destroyInactiveTabPane={false}
        activeKey={tabChange}
        tabBarExtraContent={(
          <>
            <Space>
              <Button onClick={saveOnlineTable} type={'primary'}>保存编辑</Button>
              <Button onClick={crud} type={'primary'}>保存并生成代码</Button>
            </Space>
          </>
        )}
      />
    </Card>
  );
};
