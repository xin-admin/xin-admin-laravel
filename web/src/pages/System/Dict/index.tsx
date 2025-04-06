import XinTable from '@/components/Xin/XinTable';
import { Alert, Button, Col, Empty, message, Row } from 'antd';
import { useModel } from '@umijs/max';
import { IDict } from '@/domain/iDict';
import { XinTableColumn, XinTableProps } from '@/components/Xin/XinTable/typings';
import { ProTableProps } from '@ant-design/pro-components';
import React, { useState } from 'react';
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

  const [selectedRows, setSelectedRows] = useState<IDict>({id: 1, name: '性别'});
  const [tableParams, setParams] = useState<{ keywordSearch?: string; }>();
  const tableProps: ProTableProps<IDict, any> = {
    params: tableParams,
    search: false,
    rowSelection: {
      type: 'radio',
      alwaysShowAlert: true,
      defaultSelectedRowKeys: [1],
      onSelect: (record) => {
        setSelectedRows(record);
      },
    },
    cardProps: { bordered: true },
    tableAlertRender: () => '请选择字典项',
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
      <Col span={14}>
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
      <Col span={10}>
        <Alert
          message="数据字典"
          description={"XinAdmin 提供了强大的系统字典功能，数据字典是将单选或者多选的选项作为配置，不必写死在前端编码中，比如：商品类型字典中有字典项：食品、药物、衣物、化妆品等，你就可以用字典来进行动态的配置，以便后期修改和维护。\n"}
          type="info"
          closable
          style={{marginBottom: 20}}
        />
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
                pagination: { pageSize: 10 },
                cardProps: { bordered: true },
                headerTitle: `字典项管理（${selectedRows?.name}）`
              }}
              accessName={'system.dict.item'}
              formProps={{ grid: true, colProps: { span: 12 } }}
            />
          ) : (
            <Empty description={'请选择字典'} />
          )}
      </Col>
    </Row>
  );
}
