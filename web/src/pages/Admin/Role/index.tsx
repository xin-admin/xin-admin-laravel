import XinTableV2 from '@/components/XinTableV2';
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import React, { useState } from 'react';
import { Card, Col, Row, Tree } from 'antd';
import { ProCard, ProTableProps } from '@ant-design/pro-components';
import { IRole } from '@/domain/iRole';


export default () => {
  const [selectedGroup, setSelectedGroup] = useState<IRole>();
  const columns: ProFormColumnsAndProColumns<IRole>[] = [
    {
      title: 'ID',
      dataIndex: 'role_id',
      hideInForm: true,
      hideInTable: true,
    },
    {
      title: '角色名',
      dataIndex: 'name',
      colProps: { span: 12 },
    },
    {
      title: '排序',
      dataIndex: 'sort',
      valueType: 'digit',
      colProps: { span: 12 },
    },
    {
      title: '描述',
      dataIndex: 'description',
      valueType: 'textarea',
      colProps: { span: 24 },
    },
    {
      title: '创建时间',
      dataIndex: 'created_at',
      valueType: 'fromNow',
      hideInForm: true,
    },
    {
      title: '编辑时间',
      dataIndex: 'updated_at',
      valueType: 'fromNow',
      hideInForm: true,
    },
  ];

  const tableProps: ProTableProps<IRole, any> = {
    headerTitle: '分组列表',
    search: false,
    pagination: false,
    rowSelection: {
      type: 'radio',
      alwaysShowAlert: true,
      getCheckboxProps: (record: IRole) => ({
        disabled: record.role_id === 1, // Column configuration not to be checked
        name: record.name,
      }),
    },
    cardProps: { bordered: true },
    tableAlertRender: ({selectedRows}) => selectedRows.length ? selectedRows[0].name : '请选择',
  };

  return (
    <Row gutter={[20, 20]}>
      <Col span={16}>
        <XinTableV2<IRole>
          accessName={'admin.role'}
          api={'/admin/role'}
          columns={columns}
          rowKey={'role_id'}
          deleteShow={(i) => i.role_id !== 1}
          editShow={(i) => i.role_id !== 1}
          tableProps={tableProps}
          formProps={{ grid: true }}
        />
      </Col>
      <Col span={8}>
        <ProCard bordered={true} title={'权限'}>
          {
            selectedGroup && <Tree
              checkable
              defaultExpandAll
              blockNode={false}
              fieldNames={{
                title: 'name',
                key: 'id',
                children: 'children',
              }}
              showLine
            />
          }
        </ProCard>
      </Col>
    </Row>
  );
}
