import { BetaSchemaForm, ProFormColumnsType, ProFormInstance, useDebounceFn } from '@ant-design/pro-components';
import React, { useContext, useEffect, useRef } from 'react';
import { Typography } from 'antd';
import TableConfigContext from './TableConfigContext';

export const defaultTableSetting: OnlineType.TableConfig = {
  bordered: false,
  size: 'middle',
  showHeader: false,
  rowSelectionShow: false,
  addShow: false,
  deleteShow: false,
  editShow: false,
  optionsShow: true,
  options: {
    density: true,
    search: false,
    fullScreen: false,
    setting: true,
    reload: true
  },
  headerTitle: '表格标题',
  tooltip: '表格 tooltip',
  searchShow: true,
  search: {
    searchText: '查询',
    resetText: '重置',
    span: 6,
    layout: 'vertical',
    filterType: 'query'
  },
  paginationShow: true,
  pagination: {
    size: 'default',
  }
}

const tableColumns: ProFormColumnsType<OnlineType.TableConfig>[] = [
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
    colProps: {span: 8},
  },
  {
    title: '表格新增',
    valueType: 'switch',
    dataIndex: 'addShow',
    colProps: {span: 8}
  },
  {
    title: '表格删除',
    valueType: 'switch',
    dataIndex: 'deleteShow',
    colProps: {span: 8}
  },
  {
    title: '表格编辑',
    valueType: 'switch',
    dataIndex: 'editShow',
    colProps: {span: 8}
  },
  {
    title: '表格边框',
    valueType: 'switch',
    dataIndex: 'bordered',
    colProps: {span: 8}
  },
  {
    title: '显示标题',
    valueType: 'switch',
    dataIndex: 'showHeader',
    colProps: {span: 8}
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
    colProps: {span: 8}
  },
  {
    valueType: 'dependency',
    name: ['searchShow'],
    columns: ({searchShow}) => {
      if(searchShow === false) return []
      return [
        {
          title: '重置按钮文案',
          valueType: 'text',
          dataIndex: ['search','resetText'],
        },
        {
          title: '查询按钮文案',
          valueType: 'text',
          dataIndex: ['search','searchText'],
        },
        {
          title: '表单栅格',
          valueType: 'radio',
          dataIndex: ['search','span'],
          valueEnum: new Map([
            [24,24],
            [12,12],
            [8,8],
            [6,6],
          ]),
        },
        {
          title: '表单布局',
          valueType: 'radioButton',
          dataIndex: ['search','layout'],
          fieldProps: {
            size: 'small'
          },
          valueEnum: new Map([
            ['vertical','垂直'],
            ['horizontal','水平']
          ]),
          colProps: {span: 12},
        },
        {
          title: '表单类型',
          valueType: 'radioButton',
          dataIndex: ['search','filterType'],
          valueEnum: new Map([
            ['query', '默认'],
            ['light', '轻量']
          ]),
          fieldProps: {
            size: 'small'
          },
          colProps: {span: 12},
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
    colProps: {span: 8},
  },
  {
    valueType: 'dependency',
    name: ['optionsShow'],
    columns: ({optionsShow}) => {
      if(optionsShow === false) return []
      return [
        {
          title: '刷新按钮',
          valueType: 'switch',
          dataIndex: ['options','reload'],
          colProps: {span: 8},
        },
        {
          title: '密度按钮',
          valueType: 'switch',
          dataIndex: ['options','density'],
          colProps: {span: 8},
        },
        {
          title: '一键搜索',
          valueType: 'switch',
          dataIndex: ['options','search'],
          colProps: {span: 8},
        },
        {
          title: '全屏按钮',
          valueType: 'switch',
          dataIndex: ['options','fullScreen'],
          colProps: {span: 8},
        },
        {
          title: '列设置',
          valueType: 'switch',
          dataIndex: ['options','setting'],
          colProps: {span: 8},
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
    colProps: {span: 12},
  },
  {
    valueType: 'dependency',
    name: ['paginationShow'],
    columns: ({paginationShow}) => {
      if(paginationShow === false) return []
      return [
        {
          title: '分页尺寸',
          valueType: 'radioButton',
          dataIndex: ['pagination','size'],
          valueEnum: new Map([
            ['default', '默认'],
            ['small', '小'],
          ]),
          fieldProps: {size: 'small'},
          colProps: {span: 12},
        },
        {
          title: '简介分页',
          valueType: 'switch',
          dataIndex: ['pagination','simple'],
          colProps: {span: 12},
        },
      ]
    }
  },
]


export default () => {

  const {tableConfig,setTableConfig} = useContext(TableConfigContext);

  /** 去抖配置 */
  const updateConfig = useDebounceFn(async (state: OnlineType.TableConfig) => {
    if(!state.searchShow) state.search = false
    if(!state.optionsShow) state.options = false
    if(!state.paginationShow) state.pagination = false
    setTableConfig({
      ...tableConfig,
      tableSetting: state
    })
  }, 300);

  const form = useRef<ProFormInstance>()

  useEffect(()=>{
    form.current?.setFieldsValue(tableConfig.tableSetting)
  },[tableConfig])

  return (
    <BetaSchemaForm<OnlineType.TableConfig>
      layout="inline"
      layoutType={'Form'}
      formRef={form}
      grid={true}
      onValuesChange={(changeValue, values) => updateConfig.run(values)}
      initialValues={defaultTableSetting}
      columns={tableColumns}
      submitter={{ render: () => [] }}
    />
  )
}
