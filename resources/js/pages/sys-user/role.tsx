import XinTableV2 from "@/components/XinTableV2";
import type {XinTableColumn} from "@/components/XinTableV2/typings";
import {Button, Card, type CardProps, Col, message, Row, Switch, Table, type TableProps, Tag, Tooltip, Tree, type TreeProps} from "antd";
import {type RuleFieldsList, rulesList, saveRoleRules, statusRole, users as usersApi} from "@/api/sys/sysUserRole";
import type {ISysRole} from "@/domain/iSysRole";
import React, {useEffect, useRef, useState} from "react";
import type ISysUser from "@/domain/iSysUser.ts";
import {KeyOutlined, SaveOutlined, SmileOutlined, TeamOutlined} from "@ant-design/icons";
import { useTranslation } from "react-i18next";
import { isArray } from "lodash";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

const Role = () => {
  const {t} = useTranslation();
  // 状态管理
  const [selectedRoleId, setSelectedRoleId] = useState<number | undefined>();
  const [activeTab, setActiveTab] = useState('users');
  const [roleUsers, setRoleUsers] = useState<ISysUser[]>([]);
  const [roleUsersTotal, setRoleUsersTotal] = useState<number>(0);
  const [isLoadingUsers, setIsLoadingUsers] = useState<boolean>(false);
  const [ruleFields, setRuleFields] = useState<RuleFieldsList[]>([]);
  const [checkedRuleKeys, setCheckedRuleKeys] = useState<React.Key[]>([]);
  const [isSavingRules, setIsSavingRules] = useState<boolean>(false);
  // 树展开状态
  const [expandedKeys, setExpandedKeys] = useState<React.Key[]>([]);
  // 树引用
  const treeRef = useRef<any>(null);
  // Tab 配置
  const tabList: CardProps['tabList'] = [
    {
      key: "users",
      icon: <TeamOutlined />,
      label: t('sysUserRole.tab.users'),
    },
    {
      key: "rules",
      icon: <KeyOutlined />,
      label: t('sysUserRole.tab.rules')
    }
  ];
  // 角色表格列配置
  const roleColumns: XinTableColumn<ISysRole>[] = [
    {
      title: t('sysUserRole.table.roleName'),
      dataIndex: "name",
      valueType: "text",
      align: "center",
      required: true,
      width: 120,
      rules: [{ required: true, message: t('sysUserRole.table.roleName.required') }],
      render: (value: any, record: ISysRole) => (
        <>
          <Tooltip title={record.description}>
            <Tag bordered={false} color="blue">{value}</Tag>
          </Tooltip>
        </>
      ),
    },
    {
      title: t('sysUserRole.table.sort'),
      dataIndex: "sort",
      valueType: "digit",
      hideInSearch: true,
      align: "center",
      width: 80,
      required: true,
      rules: [{ required: true, message: t('sysUserRole.table.sort.required') }],
      render: (value: number) => <Tag bordered={false} color="purple">{value}</Tag>,
    },
    {
      title: t('sysUserRole.table.userCount'),
      dataIndex: "countUser",
      valueType: "text",
      width: 80,
      hideInForm: true,
      align: "center",
      render: (value: number) => <a><u>{value}{t('sysUserRole.userTable.person')}</u></a>,
    },
    {
      title: t('sysUserRole.table.status'),
      dataIndex: "status",
      valueType: "switch",
      align: "center",
      filters: [
        { text: t('sysUserRole.table.status.disable'), value: 0 },
        { text: t('sysUserRole.table.status.enable'), value: 1 },
      ],
      hideInSearch: true,
      required: true,
      rules: [{ required: true, message: t('sysUserRole.table.status.required') }],
      render: (_, record: ISysRole) => (
        <Switch
          disabled={record.id === 1}
          checked={record.status === 1}
          checkedChildren={t('sysUserRole.table.status.enable')}
          unCheckedChildren={t('sysUserRole.table.status.disable')}
          onChange={async (_, event) => {
            event.stopPropagation();
            try {
              await statusRole(record.id!);
              message.success(t('sysUserRole.message.statusUpdateSuccess'));
            } catch (error) {
              message.error(t('sysUserRole.message.statusUpdateFailed'));
              console.error('Failed to update role status:', error);
            }
          }}
        />
      ),
    },
    {
      valueType: 'textarea',
      title: t('sysUserRole.table.description'),
      dataIndex: 'description',
      hideInTable: true,
    },
    {
      title: t('sysUserRole.table.createdAt'),
      hideInForm: true,
      hideInSearch: true,
      dataIndex: 'created_at',
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
    {
      title: t('sysUserRole.table.updatedAt'),
      hideInForm: true,
      hideInSearch: true,
      dataIndex: 'updated_at',
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
  ];
  // 用户列表表格列配置
  const userColumns: TableProps<ISysUser>['columns'] = [
    {
      title: t('sysUserRole.userTable.userId'),
      dataIndex: 'id',
      key: 'id',
      align: 'center',
      width: 80,
    },
    {
      title: t('sysUserRole.userTable.username'),
      dataIndex: 'username',
      key: 'username',
      align: 'center',
    },
    {
      title: t('sysUserRole.userTable.nickname'),
      dataIndex: 'nickname',
      key: 'nickname',
      align: 'center',
    },
    {
      title: t('sysUserRole.userTable.email'),
      dataIndex: 'email',
      key: 'email',
      align: 'center',
      ellipsis: true,
    },
    {
      title: t('sysUserRole.userTable.mobile'),
      dataIndex: 'mobile',
      key: 'mobile',
      align: 'center',
    },
    {
      title: t('sysUserRole.userTable.status'),
      dataIndex: 'status',
      key: 'status',
      align: 'center',
      render: (value: number) => {
        const status = value === 0 
          ? { color: 'error', text: t('sysUserRole.userTable.status.banned') }
          : { color: 'success', text: t('sysUserRole.userTable.status.normal') };
        return <Tag color={status.color}>{status.text}</Tag>;
      },
      width: 80,
    },
  ];
  // 获取角色用户列表
  const fetchRoleUsers = async (page: number = 1, pageSize: number = 10) => {
    if (!selectedRoleId) return;
    setIsLoadingUsers(true);
    try {
      const response = await usersApi(selectedRoleId, { page, pageSize });
      const { data, total } = response.data.data!;
      setRoleUsers(data);
      setRoleUsersTotal(total);
    } finally {
      setIsLoadingUsers(false);
    }
  }

  useEffect(() => { 
    rulesList().then(response => {
      setRuleFields(response.data.data!);
    });
   }, []);

  // 当selectedRoleId变化时，重新获取用户列表
  useEffect(() => { fetchRoleUsers(1, 10) }, [selectedRoleId]);

  // 事件处理函数
  const handleRoleSelect = (record: ISysRole) => {
    setSelectedRoleId(record.id!);
    // 回显当前角色的权限
    if (record.ruleIds && record.ruleIds.length > 0) {
      setCheckedRuleKeys(record.ruleIds);
    } else {
      setCheckedRuleKeys([]);
    }
  };

  // 渲染权限树节点
  const renderRuleTreeNode = (node: RuleFieldsList) => {
    if (node.local) {
      return (
        <>
          {t(node.local)} - <span style={{ color: '#00000040' }}>{node.title}</span>
        </>
      );
    }
    return <>{node.title}</>;
  };

  // 处理权限树勾选变化
  const handleRuleCheck: TreeProps['onCheck'] = (checkedKeys) => {
    if(isArray(checkedKeys)) {
      setCheckedRuleKeys(checkedKeys);
    }else {
      setCheckedRuleKeys(checkedKeys.checked);
    }
  };

  // 处理树展开状态变化
  const handleTreeExpand = (keys: React.Key[]) => {
    setExpandedKeys(keys);
  };

  // 获取所有节点的key
  const getAllNodeKeys = (nodes: RuleFieldsList[]): React.Key[] => {
    let keys: React.Key[] = [];
    nodes.forEach(node => {
      keys.push(node.key);
      if (node.children && node.children.length > 0) {
        keys = [...keys, ...getAllNodeKeys(node.children)];
      }
    });
    return keys;
  };

  // 展开全部节点
  const expandAll = () => {
    const allKeys = getAllNodeKeys(ruleFields);
    setExpandedKeys(allKeys);
  };

  // 折叠全部节点
  const collapseAll = () => {
    setExpandedKeys([]);
  };

  // 选择全部节点
  const selectAll = () => {
    const allKeys = getAllNodeKeys(ruleFields);
    setCheckedRuleKeys(allKeys);
  };

  // 取消选择全部节点
  const deselectAll = () => {
    setCheckedRuleKeys([]);
  };

  // 反选节点
  const invertSelection = () => {
    const allKeys = getAllNodeKeys(ruleFields);
    const currentCheckedSet = new Set(checkedRuleKeys);
    const invertedKeys = allKeys.filter(key => !currentCheckedSet.has(key));
    setCheckedRuleKeys(invertedKeys);
  };

  // 保存权限设置
  const handleSaveRules = async () => {
    if (!selectedRoleId) {
      message.warning(t('sysUserRole.message.selectRoleFirst'));
      return;
    }
    setIsSavingRules(true);
    try {
      const ruleIds = checkedRuleKeys.map(key => Number(key));
      await saveRoleRules(selectedRoleId, ruleIds);
      message.success(t('sysUserRole.message.rulesSaveSuccess'));
    } finally {
      setIsSavingRules(false);
    }
  }

  return (
    <Row gutter={[20, 20]}>
      {/* 角色列表 */}
      <Col xxl={12} lg={12} xs={24}>
        <XinTableV2<ISysRole>
          api="/sys-user/role"
          accessName="sys-user.role"
          columns={roleColumns}
          rowKey="id"
          searchShow={false}
          titleRender={<span>{t('sysUserRole.table.headerTitle')}</span>}
          searchProps={false}
          scroll={{x: 800}}
          editShow={(row) => row.id !== 1}
          deleteShow={(row) => row.id !== 1}
          formProps={{
            grid: true,
            rowProps: { gutter: [20, 0] },
            colProps: { span: 12 },
          }}
          operateProps={{
            fixed: 'right'
          }}
          rowSelection={{
            type: 'radio',
            selectedRowKeys: selectedRoleId ? [selectedRoleId] : [],
            onChange: (_, rows) => handleRoleSelect(rows[0])
          }}
          onRow ={(record) => ({
            onClick: () => handleRoleSelect(record)
          })}
        />
      </Col>

      {/* 用户列表和权限管理 */}
      <Col xxl={12} lg={12} xs={24}>
        <Card
          tabList={tabList}
          onTabChange={setActiveTab}
          activeTabKey={activeTab}
          styles={{ body: { minHeight: '70vh' } }}
        >
          { selectedRoleId ? (
            activeTab === 'users' ? (
              <Table<ISysUser>
                loading={isLoadingUsers}
                dataSource={roleUsers}
                bordered={true}
                columns={userColumns}
                size="small"
                pagination={{
                  total: roleUsersTotal,
                  showSizeChanger: true,
                  onChange: fetchRoleUsers
                }}
                scroll={{x: 600}}
              />
            ) : (
              <>
                <div style={{ height: '600px', overflow: 'auto', marginBottom: 12 }}>
                  <Tree
                    ref={treeRef}
                    checkable
                    treeData={ruleFields}
                    checkedKeys={checkedRuleKeys}
                    onCheck={handleRuleCheck}
                    titleRender={renderRuleTreeNode}
                    checkStrictly={true}
                    expandedKeys={expandedKeys}
                    onExpand={handleTreeExpand}
                  />
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8, flexWrap: 'wrap' }}>
                  <span style={{ color: '#666', fontSize: 12 }}>
                    {t('sysUserRole.permission.selectedCount', { count: checkedRuleKeys.length })}
                  </span>
                  <Button size="small" onClick={expandAll}>{t('sysUserRole.button.expandAll')}</Button>
                  <Button size="small" onClick={collapseAll}>{t('sysUserRole.button.collapseAll')}</Button>
                  <Button size="small" onClick={selectAll}>{t('sysUserRole.button.selectAll')}</Button>
                  <Button size="small" onClick={deselectAll}>{t('sysUserRole.button.clearAll')}</Button>
                  <Button size="small" onClick={invertSelection}>{t('sysUserRole.button.invertSelection')}</Button>
                  <Button
                    type="primary"
                    icon={<SaveOutlined />}
                    loading={isSavingRules}
                    onClick={handleSaveRules}
                    size="small"
                    disabled={selectedRoleId === 1}
                  >
                    {t('sysUserRole.button.saveRules')}
                  </Button>
                </div>
              </>
            )
          ) : (
            <div style={{ textAlign: 'center', color: '#00000040' }}>
              <SmileOutlined style={{ fontSize: 40, marginBottom: 12 }} />
              <p>{t('sysUserRole.placeholder.selectRole')}</p>
            </div>
          )}
        </Card>
      </Col>
    </Row>
  );
}

export default Role;
