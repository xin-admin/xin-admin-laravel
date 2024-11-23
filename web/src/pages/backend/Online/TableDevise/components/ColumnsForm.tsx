import {
  EditableProTable,
  DragSortTable,
  ProColumns,
  useDebounceFn,
  BetaSchemaForm,
  ProFormColumnsType, ProFormInstance,
} from '@ant-design/pro-components';
import { useModel } from '@umijs/max';
import { defaultSql } from './defaultData';
import { Button, Col, Row, Space } from 'antd';
import React, { useEffect, useRef, useState } from 'react';
import { DeleteOutlined } from '@ant-design/icons';

export default (props: {
  tableConfig: OnlineType.OnlineTableType;
  setTableConfig: (newTableConfig: OnlineType.OnlineTableType) => void;
}) => {

  const isMenu = ['select', 'checkbox', 'radio', 'radioButton'];
  const { dictEnum } = useModel('dictModel');
  const {tableConfig, setTableConfig} = props;
  const tableSettingForm = useRef<ProFormInstance>();
  const [editableKeys, setEditableRowKeys] = useState<React.Key[]>(tableConfig.columns.map((i: any) => i.key));
  // 字段配置
  const columnsFormColumns: ProColumns<OnlineType.ColumnsConfig>[] = [
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
      editable: false
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
      valueType: 'checkbox',
      width: 80,
      align: 'center',
    },
    {
      title: '表格禁用',
      dataIndex: 'hideInTable',
      valueType: 'checkbox',
      align: 'center',
      valueEnum: {"false": "1"},
      width: 80,
    },
    {
      title: '表单禁用',
      dataIndex: 'hideInForm',
      valueType: 'checkbox',
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
    {
      title: '操作',
      valueType: 'option',
      width: 80,
      align: 'center',
      fixed: 'right',
    },
  ];

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
    setEditableRowKeys(arr.map(i => i.key! ))
  }

  // useEffect(() => {
  //   setEditableRowKeys(tableConfig.columns.map((i: any) => i.key))
  // }, [tableConfig.columns])

  // 编辑字段 去抖配置
  const updateColumnsConfig = useDebounceFn(async (state) => {
    setTableConfig({ ...tableConfig, columns: state })
  }, 300);

  // 排序
  const handleDragSortEnd = ( _: any, __: any, newDataSource: any) => {
    setTableConfig({ ...tableConfig, columns: newDataSource })
  };

  return (
    <>
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
          onChange: setEditableRowKeys
        }}
      />
    </>
  )
}
