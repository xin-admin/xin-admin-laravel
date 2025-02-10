import XinTableV2 from '@/components/XinTableV2';
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import React, { useEffect, useState } from 'react';
import { Button, Col, message, Row, Space, Tree } from 'antd';
import { ProCard, ProTableProps } from '@ant-design/pro-components';
import { IRole } from '@/domain/iRole';
import { IRule } from '@/domain/iRule';
import { getApi, listApi } from '@/services/common/table';
import { useBoolean } from 'ahooks';
import { request } from '@umijs/max';

export default () => {
  const columns: ProFormColumnsAndProColumns<IRole>[] = [
    {
      title: '编号',
      dataIndex: 'role_id',
      hideInForm: true,
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

  const [treeData, setTreeData] = useState();
  const [checkedKeys, setCheckedKeys] = useState<React.Key[]>([]);
  const [selectedRows, setSelectedRows] = useState<IRole>();
  const [ruleLoading, setRuleLoading] = useBoolean(false);

  useEffect(() => {
    setRuleLoading.setTrue();
    listApi('/admin/rule').then((res) => {
      setTreeData(res.data.data);
    }).finally(() => {
      setRuleLoading.setFalse();
    });
  }, []);

  useEffect(() => {
    if (selectedRows) {
      setRuleLoading.setTrue();
      getApi('/admin/role', selectedRows?.role_id).then((res) => {
        console.log(res);
        setCheckedKeys(res.data.rules);
      }).finally(() => {
        setRuleLoading.setFalse();
      });
    }
  }, [selectedRows]);

  /** 获取所有Kye */
  const collectKeys = (data: IRule[]) => {
    let keys: any = [];
    data.forEach((item) => {
      keys.push(item.key);
      if (item.children) {
        keys = keys.concat(collectKeys(item.children));
      }
    });
    return keys;
  };

  /** 树状列表标题 */
  const treeTitleRender = (i: IRule) => (
    <Space>
      {i.name}
      <span style={{ color: 'rgba(0, 0, 0, 0.25)', fontSize: 12 }}>
        {Number(i.type) === 0 && <>一级菜单</>}
        {Number(i.type) === 1 && <>子菜单</>}
        {Number(i.type) === 2 && <>按钮/API</>}
      </span>
      <span style={{ color: 'rgba(0, 0, 0, 0.25)', fontSize: 12 }}>
        {i.key}
      </span>
    </Space>
  );

  /** 保存权限编辑 */
  const saveRule = () => {
    setRuleLoading.setTrue();
    request('/admin/role/rule', {
      method: 'POST',
      data: {
        role_id: selectedRows?.role_id,
        rule_keys: checkedKeys,
      },
    }).then(() => {
      message.success('保存成功');
    }).finally(() => {
      setRuleLoading.setFalse();
    });
  };

  /** 表格配置 */
  const tableProps: ProTableProps<IRole, any> = {
    headerTitle: '角色列表',
    search: false,
    rowSelection: {
      type: 'radio',
      alwaysShowAlert: true,
      getCheckboxProps: (record: IRole) => ({
        disabled: record.role_id === 1, // Column configuration not to be checked
        name: record.name,
      }),
      onSelect: (record) => {
        setSelectedRows(record);
      },
    },
    toolbar: { settings: []},
    cardProps: { bordered: true },
    tableAlertRender: ({ selectedRows }) => selectedRows.length ? selectedRows[0].name : '请选择',
    tableAlertOptionRender: false,
  };

  return (
    <Row gutter={[20, 20]}>
      <Col flex={'1 1 200px'}>
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
      <Col flex={'0 1 600px'}>
        <ProCard bordered={true} title={'权限'} loading={ruleLoading} extra={(
          <>
            {selectedRows &&
              <Space>
                <Button type={'primary'} onClick={() => saveRule()}>保存编辑</Button>
                <Button onClick={() => setCheckedKeys(collectKeys(treeData!))}>全部选择</Button>
                <Button onClick={() => setCheckedKeys([])}>全部取消</Button>
              </Space>
            }
          </>
        )}>
          <div style={{ maxHeight: '70vh', overflowY: 'auto' }}>
            {
              treeData && <Tree<IRule>
                disabled={!selectedRows}
                checkable
                treeData={treeData}
                defaultExpandAll
                checkedKeys={checkedKeys}
                fieldNames={{
                  title: 'name',
                  key: 'key',
                  children: 'children',
                }}
                titleRender={treeTitleRender}
                checkStrictly
                onCheck={(checkedKeys) => {
                  if (Array.isArray(checkedKeys)) {
                    setCheckedKeys(checkedKeys);
                  } else {
                    setCheckedKeys(checkedKeys.checked);
                  }
                  console.log(checkedKeys);
                }}
                showLine
              />
            }
          </div>
        </ProCard>
      </Col>
    </Row>
  );
}
