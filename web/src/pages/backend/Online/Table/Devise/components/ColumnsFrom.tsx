import { Button, Col, message, Row, Space } from 'antd';
import React, { useContext, useState } from 'react';
import { EditableProTable, ProColumns, useDebounceFn } from '@ant-design/pro-components';
import { useModel } from '@umijs/max';
import defaultSql from './defaultSql';
import TableConfigContext from './TableConfigContext';


const CardStyle: React.CSSProperties = {
  width: '100vw',
  height: '100vh',
  position: 'fixed',
  top: 0,
  left: 0,
  zIndex: 9999,
  background: '#fff',
  paddingBottom: 100,
  overflow: 'auto',
}

const BottomButtonStyle: React.CSSProperties = {
  padding: 20,
  background: '#fff',
  width: '100%',
  zIndex: 100,
  flexDirection: 'row-reverse'
}


function CreateForm() {
  const { tableConfig, setTableConfig } = useContext(TableConfigContext);
  const { getDictionaryData, dictEnum } = useModel('dictModel');
  const [modelShow, setModelShow] = useState<boolean>(false);
  const [dataSource, setDataSource] = useState<OnlineType.ColumnsConfig[]>([]);
  const [editableKeys, setEditableRowKeys] = useState<React.Key[]>([]);
  const isMenu = ['select', 'checkbox', 'radio', 'radioButton'];

  const columns: ProColumns<OnlineType.ColumnsConfig>[] = [
    {
      title: '基本配置',
      children: [
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
          fixed: 'left',
          width: 160,
        },
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
          width: 160,
          align: 'center',
          fixed: 'left',
        },
        {
          title: '字段备注',
          dataIndex: 'title',
          valueType: 'text',
          tooltip: '作为表格表头和表单项名称',
          width: 160,
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
          request: async () => await getDictionaryData('select'),
          valueType: 'text',
          width: 140,
        },
        {
          title: '验证规则',
          dataIndex: 'validation',
          valueType: 'select',
          request: async () => await getDictionaryData('validation'),
          align: 'center',
          fieldProps: { mode: 'multiple' },
          tooltip: '内置部分验证规则，需要自定义验证规则请看文档',
          width: 140,
        },
        {
          title: '搜索隐藏',
          dataIndex: 'hideInSearch',
          valueType: 'switch',
          width: 100,
          align: 'center',
        },
        {
          title: '表格隐藏',
          dataIndex: 'hideInTable',
          valueType: 'switch',
          width: 100,
          align: 'center',
        },
        {
          title: '表单隐藏',
          dataIndex: 'hideInForm',
          valueType: 'switch',
          width: 100,
          align: 'center',
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
          width: 160,
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
      title: '数据库配置',
      children: [
        {
          title: '字段类型',
          dataIndex: 'sqlType',
          valueType: 'text',
          initialValue: 'null',
          tooltip: '请输入正确的数据库类型！',
          align: 'center',
          width: 160,
        },
        {
          title: '字段长度',
          dataIndex: 'sqlLength',
          valueType: 'digit',
          align: 'center',
          width: 120,
        },
        {
          title: '字段默认值',
          dataIndex: 'defaultValue',
          valueType: 'text',
          initialValue: 'null',
          tooltip: '支持 null（NULL） 和 empty string（EMPTY STRING） 以及其它非空字符串',
          align: 'center',
          width: 160,
        },
        {
          title: '是否主键',
          dataIndex: 'isKey',
          valueType: 'switch',
          tooltip: '主键只能有一个',
          initialValue: false,
          width: 100,
          align: 'center',
        },
        {
          title: '不为空',
          dataIndex: 'null',
          valueType: 'switch',
          initialValue: false,
          width: 100,
          align: 'center',
        },
        {
          title: '自动递增',
          dataIndex: 'autoIncrement',
          valueType: 'switch',
          initialValue: false,
          width: 100,
          align: 'center',
        },
        {
          title: '无符号',
          dataIndex: 'unsign',
          valueType: 'switch',
          width: 100,
          align: 'center',
        },
      ],
    },
    {
      title: 'Mock 模拟数据',
      dataIndex: 'mock',
      valueType: 'text',
      align: 'center',
      tooltip: <>模拟数据格式，请查看文档 <a href={'http://mockjs.com/examples.html'} rel='noreferrer' target='_blank'>Mock</a> </>,
      initialValue: '@string',
      width: 220,
    },
    {
      title: '操作',
      valueType: 'option',
      width: 100,
      align: 'center',
      fixed: 'right',
    },
  ];

  /**
   * 保存字段并返回
   */
  const onSave = () => {
    let reDataIndex = dataSource.some((item: any, index: any) => {
      return dataSource.some((innerItem: any, innerIndex: any) => {
        return index !== innerIndex && item.dataIndex === innerItem.dataIndex && item.title === innerItem.title;
      });
    });
    if (reDataIndex) {
      message.warning('字段种存在相同字段名或字段备注！');
      return;
    }
    setTableConfig({
      ...tableConfig,
      columns: dataSource
    });
    message.success('保存字段成功！');
    setModelShow(false);
  };

  /** 去抖配置 */
  const updateConfig = useDebounceFn(async (state) => {
    setDataSource(state);
  }, 200);

  /**
   * 添加字段
   * @param createData 字段初始值
   */
  const createColumns = (createData: OnlineType.ColumnsConfig) => {
    let arr = [
      ...dataSource,
      {
        key: createData.dataIndex! + Date.now().toString(),
        valueType: createData.valueType,
        title: createData.title! + dataSource.length,
        dataIndex: createData.dataIndex! + dataSource.length,
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
    setDataSource(arr)
    setEditableRowKeys(arr.map((item: any) => item.key))
  }

  return (
    <>
      <Button
        onClick={() => {
          setDataSource(tableConfig.columns)
          setEditableRowKeys(tableConfig.columns.map((i: any) => i.key))
          setModelShow(true)
        }}
        type={'primary'}
        block
        style={{ marginTop: 10 }}
      >
        编辑字段
      </Button>
      {modelShow &&
        <div style={CardStyle}>
          <Row style={{ padding: 20 }}>
            <Col span={20}>
              {defaultSql.map((item, index) => (
                <div style={{ padding: '0 20px', marginBottom: 10 }} key={index}>
                  <Space wrap={true}>
                    <span>{item.title}：</span>
                    {item.component.map((component) => {
                      return <Button type="dashed" key={component.title} onClick={() => createColumns(component)} >{component.title}</Button>
                    })}
                  </Space>
                </div>
              ))}
            </Col>
            <Col span={4} style={BottomButtonStyle}>
              <Button onClick={() => setModelShow(false)} danger type={'primary'}>取消保存并返回</Button>
              <Button style={{ marginLeft: 10 }} onClick={onSave} type={'primary'}>保存并返回</Button>
            </Col>
          </Row>
          <EditableProTable
            columns={columns}
            rowKey='key'
            scroll={{ x: 1600 }}
            value={dataSource}
            bordered
            recordCreatorProps={false}
            size={'middle'}
            editable={{
              type: 'multiple',
              editableKeys,
              actionRender: (record) => [
                <a key='delete' onClick={() => {
                  console.log(123);

                  setDataSource(dataSource.filter((item: any) => item.dataIndex !== record.dataIndex));
                }}
                >删除</a>
              ],
              onValuesChange: (record, recordList) => { updateConfig.run(recordList) }
            }}
          />
        </div>
      }
    </>
  );
}

export default CreateForm;
