import { XinTableColumn } from '@/components/XinTableV2/typings';
import { IAdminDept } from '@/domain/adminDept';
import XinTableV2 from '@/components/XinTableV2';
import { listApi } from '@/services/common/table';
import React, { useState } from 'react';
import { IRule } from '@/domain/rule';
import { Button } from 'antd';

export default () => {

  const columns: XinTableColumn<IAdminDept>[] = [
    {
      title: '部门ID',
      dataIndex: 'dept_id',
      hideInForm: true,
      hideInTable: true,
    },
    {
      title: '部门名称',
      dataIndex: 'name',
      valueType: 'text',
    },
    {
      title: '排序',
      dataIndex: 'sort',
      valueType: 'digit',
    },
    {
      title: '状态',
      dataIndex: 'status',
      valueType: 'radio',
      valueEnum: {
        0: { text: '禁用', status: 'Error' },
        1: { text: '启用', status: 'Success' },
      },
    },
    {
      title: '上级部门',
      dataIndex: 'parent_id',
      valueType: 'treeSelect',
      initialValue: 1,
      hideInTable: true,
      request: async  () => {
        let data = await listApi('/admin/dept');
        return data.data.data
      },
      fieldProps: { fieldNames: { label: 'name', value: 'dept_id' } }
    },
    {
      title: '部门负责人',
      dataIndex: 'leader',
      valueType: 'text',
    },
    {
      title: '部门邮箱',
      dataIndex: 'email',
      valueType: 'text',
    },
    {
      title: '部门电话',
      dataIndex: 'phone',
      valueType: 'text',
    },
    {
      title: '创建时间',
      dataIndex: 'created_at',
      valueType: 'fromNow',
      hideInForm: true
    },
    {
      title: '更新时间',
      dataIndex: 'updated_at',
      valueType: 'fromNow',
      hideInForm: true
    }
  ];

  const [expandedRowKeys, setExpandedRowKeys] = useState<React.Key[]>([]);
  const [allKeys, setAllKeys] = useState([]);
  const collectKeys = (data: IAdminDept[]) => {
    let keys: any = [];
    data.forEach((item) => {
      keys.push(item.dept_id);
      if (item.children) {
        keys = keys.concat(collectKeys(item.children));
      }
    });
    return keys;
  };


  return (
    <XinTableV2<IAdminDept>
      api={'/admin/dept'}
      rowKey={'dept_id'}
      columns={columns}
      accessName={'admin.dept'}
      toolBarRender={[
        <Button onClick={() => setExpandedRowKeys(allKeys)}>
          展开全部
        </Button>,
        <Button onClick={() => setExpandedRowKeys([])}>
          折叠全部
        </Button>
      ]}
      tableProps={{
        search: false,
        headerTitle: '部门管理',
        postData: (data: IAdminDept[]) => {
          let keys = collectKeys(data);
          setAllKeys(keys);
          setExpandedRowKeys(keys);
          return data;
        },
        expandable: {
          expandRowByClick: true,
          expandedRowKeys: expandedRowKeys,
          onExpandedRowsChange: (expandedKeys) => {
            console.log(expandedKeys);
            setExpandedRowKeys([...expandedKeys])
          }
        }
      }}
    />
  )
}
