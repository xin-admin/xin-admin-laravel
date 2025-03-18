import XinTable from '@/components/Xin/XinTable';
import { Button, Col, Empty, message, Row } from 'antd';
import { useModel } from '@umijs/max';
import { IDict } from '@/domain/iDict';
import { XinTableColumn, XinTableProps } from '@/components/Xin/XinTable/typings';
import { ProCard, ProTableProps } from '@ant-design/pro-components';
import { useState } from 'react';
import { IDictItem } from '@/domain/iDictItem';
import { addApi, editApi } from '@/services/common/table';

/** 字典管理 */
export default () => {
  const { refreshDict } = useModel('dictModel');
  // 字典
  const columns: XinTableColumn<IDict>[] = [
    { title: '字典ID', dataIndex: 'id', hideInForm: true, sorter: true },
    { title: '字典名称', dataIndex: 'name', valueType: 'text', colProps: { span: 8 } },
    { title: '字典编码', dataIndex: 'code', valueType: 'text', colProps: { span: 8 } },
    {
      title: '类型', dataIndex: 'type', valueType: 'select', filters: true, colProps: { span: 8 },
      valueEnum: {
        default: { text: '文字', status: 'Success' },
        badge: { text: '徽标', status: 'Success' },
        tag: { text: '标签', status: 'Success' },
      },
    },
    { title: '描述', dataIndex: 'describe', valueType: 'textarea', colProps: { span: 24 }, hideInSearch: true },
    { title: '创建时间', dataIndex: 'created_at', valueType: 'date', hideInForm: true },
    { title: '修改时间', dataIndex: 'updated_at', valueType: 'date', hideInForm: true },
  ];
  // 字典项
  const itemColumns: XinTableColumn<IDictItem>[] = [
    { title: 'ID', dataIndex: 'id', hideInForm: true, hideInTable: true },
    { title: '名称', dataIndex: 'label', valueType: 'text' },
    { title: '值', dataIndex: 'value', valueType: 'text' },
    {
      title: '类型', dataIndex: 'status', valueType: 'text', initialValue: 'default',
      valueEnum: {
        success: { text: 'success', status: 'Success' },
        error: { text: 'error', status: 'Error' },
        default: { text: 'default', status: 'Default' },
        processing: { text: 'processing', status: 'Processing' },
        warning: { text: 'warning', status: 'Warning' },
      },
    },
    { title: '状态', dataIndex: 'switch', valueType: 'switch', initialValue: true },
    { title: '创建时间', dataIndex: 'create_time', valueType: 'date', hideInForm: true, hideInTable: true },
    { title: '修改时间', dataIndex: 'update_time', valueType: 'date', hideInForm: true, hideInTable: true },
  ];

  const [selectedRows, setSelectedRows] = useState<IDict>();
  const [tableParams, setParams] = useState<{ keywordSearch?: string; }>();
  const tableProps: ProTableProps<IDict, any> = {
    params: tableParams,
    search: false,
    rowSelection: {
      type: 'radio',
      alwaysShowAlert: true,
      onSelect: (record) => {
        setSelectedRows(record);
      },
    },
    cardProps: { bordered: true },
    tableAlertRender: ({ selectedRows }) => selectedRows.length ? selectedRows[0].name : '请选择',
    tableAlertOptionRender: false,
    toolbar: {
      search: {
        placeholder: '请输入字典ID、字典名、字典编码搜索',
        style: { width: 304 },
        onSearch: (value: string) => {
          setParams({ keywordSearch: value });
        },
      },
      settings: [],
    },
    optionsRender: (_, dom) => {
      return [<Button type="primary" key={'ref'} onClick={() => {
        refreshDict();
      }}>刷新字典缓存</Button>, ...dom];
    },
  };

  const handleAddItem: XinTableProps<IDictItem>['onFinish'] = async (formData, initValue) => {
    if (!selectedRows) {
      message.warning('请选择字典！');
      return false;
    }
    if (initValue && initValue.id) { // 编辑
      let data = Object.assign(initValue, formData);
      await editApi('/system/dict/item', data);
      message.success('编辑成功');
      refreshDict();
      return true;
    } else { // 新增
      let data = Object.assign({ dict_id: selectedRows.id }, formData);
      await addApi('/system/dict/item', data);
      message.success('添加成功');
      refreshDict();
      return true;
    }

  };

  return (
    <Row gutter={20}>
      <Col span={16}>
        <XinTable<IDict>
          api={'/system/dict'}
          columns={columns}
          rowKey={'id'}
          tableProps={tableProps}
          accessName={'system.dict'}
          formProps={{
            grid: true,
            colProps: { span: 12 },
          }}
        />
      </Col>
      <Col span={8}>
        <ProCard title={'字典项'} bordered>
          {selectedRows ? (
            <XinTable<IDictItem>
              api={'/system/dict/item'}
              columns={itemColumns}
              rowKey={'id'}
              onFinish={handleAddItem}
              tableProps={{
                search: false,
                params: { dict_id: selectedRows.id },
                toolbar: { settings: [] },
                pagination: { pageSize: 10 }
              }}
              accessName={'system.dict.item'}
              formProps={{ grid: true, colProps: { span: 12 } }}
            />
          ) : (
            <Empty description={'请选择字典'} />
          )}
        </ProCard>
      </Col>
    </Row>
  );
}
