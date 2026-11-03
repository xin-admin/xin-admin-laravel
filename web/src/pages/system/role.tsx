import XinTable from "@/components/XinTable";
import type {XinTableColumn} from "@/components/XinTable/typings.ts";
import {
  Button, Drawer, message, Switch, Table, type TableProps, Tag, Tooltip, Tree, type TreeProps, Typography
} from "antd";
import {type RuleFieldsList, rulesList, setRule, statusRole, users as usersApi} from "@/api/system/sys_role";
import type {ISysRole} from "@/domain/iSysRole.ts";
import React, {useEffect, useRef, useState} from "react";
import type ISysUser from "@/domain/iSysUser.ts";
import { useTranslation } from "react-i18next";
import { isArray } from "lodash";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import {SaveOutlined} from "@ant-design/icons";

dayjs.extend(relativeTime);

const { Title, Text } = Typography;

const Role = () => {
  const {t} = useTranslation();
  // 状态管理
  const [selectedRoleId, setSelectedRoleId] = useState<number | undefined>();
  const [roleUsers, setRoleUsers] = useState<ISysUser[]>([]);
  const [roleUsersTotal, setRoleUsersTotal] = useState<number>(0);
  const [isLoadingUsers, setIsLoadingUsers] = useState<boolean>(false);
  const [ruleFields, setRuleFields] = useState<RuleFieldsList[]>([]);
  const [checkedRuleKeys, setCheckedRuleKeys] = useState<React.Key[]>([]);
  const [isSavingRules, setIsSavingRules] = useState<boolean>(false);

  const [ruleShow, setRuleShow] = useState<boolean>(false);
  const [userListShow, setUserListShow] = useState<boolean>(false);

  // 树展开状态
  const [expandedKeys, setExpandedKeys] = useState<React.Key[]>([]);
  // 树引用
  const treeRef = useRef<any>(null);

  // 角色表格列配置
  const roleColumns: XinTableColumn<ISysRole>[] = [
    {
      title: t('system.role.table.roleName'),
      dataIndex: "name",
      valueType: "text",
      align: "center",
      required: true,
      colProps: {span: 8},
      rules: [{ required: true, message: t('system.role.table.roleName.required') }],
      render: (value: any, record: ISysRole) => (
        <>
          <Tooltip title={record.description}>
            <Tag variant="filled" color="blue">{value}</Tag>
          </Tooltip>
        </>
      ),
    },
    {
      title: t('system.role.table.sort'),
      dataIndex: "sort",
      valueType: "digit",
      hideInSearch: true,
      align: "center",
      required: true,
      colProps: {span: 8},
      rules: [{ required: true, message: t('system.role.table.sort.required') }],
      render: (value: number) => <Tag variant="filled" color="purple">{value}</Tag>,
    },
    {
      title: t('system.role.table.userCount'),
      dataIndex: "countUser",
      valueType: "text",
      hideInForm: true,
      align: "center",
      render: (value: number) => <a><u>{value}{t('system.role.userTable.person')}</u></a>,
    },
    {
      title: t('system.role.table.status'),
      dataIndex: "status",
      valueType: "switch",
      align: "center",
      colProps: {span: 8},
      filters: [
        { text: t('system.role.table.status.disable'), value: 0 },
        { text: t('system.role.table.status.enable'), value: 1 },
      ],
      hideInSearch: true,
      required: true,
      rules: [{ required: true, message: t('system.role.table.status.required') }],
      render: (_, record: ISysRole) => (
        <Switch
          disabled={record.id === 1}
          checked={record.status === 1}
          checkedChildren={t('system.role.table.status.enable')}
          unCheckedChildren={t('system.role.table.status.disable')}
          onChange={async (_, event) => {
            event.stopPropagation();
            try {
              await statusRole(record.id!);
              message.success(t('system.role.message.statusUpdateSuccess'));
            } catch (error) {
              message.error(t('system.role.message.statusUpdateFailed'));
              console.error('Failed to update role status:', error);
            }
          }}
        />
      ),
    },
    {
      valueType: 'textarea',
      title: t('system.role.table.description'),
      dataIndex: 'description',
      colProps: {span: 24},
      hideInTable: true,
    },
    {
      title: t('system.role.table.createdAt'),
      hideInForm: true,
      hideInSearch: true,
      dataIndex: 'created_at',
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
    {
      title: t('system.role.table.updatedAt'),
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
      title: t('system.role.userTable.userId'),
      dataIndex: 'id',
      key: 'id',
      align: 'center',
      width: 80,
    },
    {
      title: t('system.role.userTable.username'),
      dataIndex: 'username',
      key: 'username',
      align: 'center',
    },
    {
      title: t('system.role.userTable.nickname'),
      dataIndex: 'nickname',
      key: 'nickname',
      align: 'center',
    },
    {
      title: t('system.role.userTable.email'),
      dataIndex: 'email',
      key: 'email',
      align: 'center',
      ellipsis: true,
    },
    {
      title: t('system.role.userTable.mobile'),
      dataIndex: 'mobile',
      key: 'mobile',
      align: 'center',
    },
    {
      title: t('system.role.userTable.status'),
      dataIndex: 'status',
      key: 'status',
      align: 'center',
      render: (value: number) => {
        const status = value === 0
          ? { color: 'error', text: t('system.role.userTable.status.banned') }
          : { color: 'success', text: t('system.role.userTable.status.normal') };
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

  // 打开角色权限弹窗
  const handleRoleOpen = (record: ISysRole) => {
    setSelectedRoleId(record.id!);
    // 回显当前角色的权限
    if (record.ruleIds && record.ruleIds.length > 0) {
      setCheckedRuleKeys(record.ruleIds);
    } else {
      setCheckedRuleKeys([]);
    }
    setRuleShow(true);
  };

  // 打开角色用户弹窗
  const handleRoleUserOpen = async (record: ISysRole) => {
    setSelectedRoleId(record.id!);
    setUserListShow(true);
    await fetchRoleUsers(1, 10);
  }

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
      message.warning(t('system.role.message.selectRoleFirst'));
      return;
    }
    setIsSavingRules(true);
    try {
      const ruleIds = checkedRuleKeys.map(key => Number(key));
      await setRule(selectedRoleId, ruleIds);
      message.success(t('system.role.message.rulesSaveSuccess'));
    } finally {
      setIsSavingRules(false);
    }
  }

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>{t('system.role.page.title')}</Title>
        <Text type="secondary">{t('system.role.page.description')}</Text>
      </div>
      <XinTable<ISysRole>
        api="/system/role"
        accessName="system.role"
        columns={roleColumns}
        rowKey="id"
        searchShow={false}
        searchProps={false}
        scroll={{x: 800}}
        editShow={(row) => row.id !== 1}
        deleteShow={(row) => row.id !== 1}
        formProps={{
          grid: true,
          rowProps: { gutter: [20, 0] },
          layout: 'vertical'
        }}
        modalProps={{ width: 800 }}
        operateProps={{ fixed: 'right' }}
        operateRender={(record, dom) => {
          return [
            <Button
              size={'small'}
              type={'primary'}
              children={'权限配置'}
              onClick={() => handleRoleOpen(record)}
            />,
            <Button
              size={'small'}
              type={'primary'}
              children={'用户列表'}
              onClick={() => handleRoleUserOpen(record)}
            />,
            dom.edit,
            dom.del
          ]
        }}
      />

      <Drawer
        title="Resizable Drawer"
        placement={'right'}
        onClose={() => setUserListShow(false)}
        open={userListShow}
        size={800}
      >
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
      </Drawer>

      <Drawer
        title="Resizable Drawer"
        placement={'right'}
        onClose={() => setRuleShow(false)}
        open={ruleShow}
        size={600}
      >
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
        <div style={{ display: 'flex', alignItems: 'center', gap: 8, flexWrap: 'wrap' }}>
          <span style={{ color: '#666', fontSize: 12 }}>
            {t('system.role.permission.selectedCount', { count: checkedRuleKeys.length })}
          </span>
          <Button size="small" onClick={expandAll}>{t('system.role.button.expandAll')}</Button>
          <Button size="small" onClick={collapseAll}>{t('system.role.button.collapseAll')}</Button>
          <Button size="small" onClick={selectAll}>{t('system.role.button.selectAll')}</Button>
          <Button size="small" onClick={deselectAll}>{t('system.role.button.clearAll')}</Button>
          <Button size="small" onClick={invertSelection}>{t('system.role.button.invertSelection')}</Button>
          <Button
            type="primary"
            icon={<SaveOutlined />}
            loading={isSavingRules}
            onClick={handleSaveRules}
            size="small"
            disabled={selectedRoleId === 1}
          >
            {t('system.role.button.saveRules')}
          </Button>
        </div>
      </Drawer>
    </>
  );
}

export default Role;
