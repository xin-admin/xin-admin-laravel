import React, { useState, useMemo, useCallback, useEffect } from 'react';
import { Modal, Tag, Select, Table, Input, type TableProps, type TableColumnType } from 'antd';
import type ISysUser from '@/domain/iSysUser';
import { List } from '@/api/common/table';
import { useTranslation } from 'react-i18next';

/**
 * 用户选择器组件属性
 */
export interface UserSelectorProps {
  value?: number | number[] | ISysUser | ISysUser[] | null;
  onChange?: (value: number | number[] | null) => void;
  mode?: 'single' | 'multiple';
  placeholder?: string;
  disabled?: boolean;
  readonly?: boolean;
  showDept?: boolean;
  maxTagCount?: number;
}

/**
 * 用户选择器组件
 */
const UserSelector: React.FC<UserSelectorProps> = ({
  value,
  onChange,
  mode = 'single',
  placeholder,
  disabled = false,
  readonly = false,
  showDept = true,
  maxTagCount = 2,
}) => {
  const { t } = useTranslation();
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [selectedUsers, setSelectedUsers] = useState<ISysUser[]>([]);
  const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([]);
  // 表格加载状态
  const [tableLoading, setTableLoading] = useState(false);
  // 表格数据
  const [dataSource, setDataSource] = useState<ISysUser[]>([]);
  // 分页参数
  const [pagination, setPagination] = useState({ current: 1, pageSize: 10, total: 0 });
  /** 表格查询参数 */
  const [tableParams, setParams] = useState<{ keywordSearch: string }>();

  // 从 value 加载用户信息
  useEffect(() => {
    if (!value) {
      setSelectedUsers([]);
      setSelectedRowKeys([]);
      return;
    }

    const valueArray = Array.isArray(value) ? value : [value];
    
    // 如果是用户对象，直接使用
    if (typeof valueArray[0] === 'object') {
      setSelectedUsers(valueArray as ISysUser[]);
      setSelectedRowKeys(valueArray.map((u: any) => u.id));
      return;
    }

    // 从 API 加载用户信息
    setLoading(true);
    Promise.all(valueArray.map(id => List<ISysUser>('/sys-user/list', { id: id as number })))
      .then(results => {
        const users = results.map(res => res.data.data?.data?.[0]).filter(Boolean) as ISysUser[];
        setSelectedUsers(users);
        setSelectedRowKeys(users.map(u => u.id!));
      })
      .catch(error => console.error('Failed to load users:', error))
      .finally(() => setLoading(false));
  }, [value]);

  // 表格列配置
  const columns: TableColumnType<ISysUser>[] = useMemo(() => [
    {
      title: t('sysUserList.id'),
      dataIndex: 'id',
      width: 80,
      align: 'center',
    },
    {
      title: t('sysUserList.username'),
      dataIndex: 'username',
      width: 120,
      align: 'center',
    },
    {
      title: t('sysUserList.nickname'),
      dataIndex: 'nickname',
      width: 120,
      align: 'center',
    },
    {
      title: t('sysUserList.mobile'),
      dataIndex: 'mobile',
      width: 130,
      align: 'center',
    },
    ...(showDept ? [{
      title: t('sysUserList.dept'),
      dataIndex: 'dept_name',
      width: 120,
      align: 'center' as const,
      render: (text: any) => text ? <Tag color="volcano">{text}</Tag> : '-',
    }] : []),
    {
      title: t('sysUserList.status'),
      dataIndex: 'status',
      width: 100,
      align: 'center',
      render: (value: number) => {
        return value === 1 
          ? <Tag color="success">{t('sysUserList.status.1')}</Tag>
          : <Tag color="error">{t('sysUserList.status.0')}</Tag>;
      },
    },
  ], [t, showDept]);

  // 处理选择确认
  const handleSelect = useCallback(() => {
    onChange?.(mode === 'single' ? (selectedUsers[0]?.id || null) : selectedUsers.map(u => u.id!));
    setOpen(false);
  }, [mode, selectedUsers, onChange]);

  // 处理行选择
  const handleRowSelectionChange = useCallback((keys: React.Key[], rows: ISysUser[]) => {
    if (mode === 'single' && keys.length > 1) {
      const lastKey = keys[keys.length - 1];
      const lastRow = rows.find(r => r.id === lastKey)!;
      setSelectedRowKeys([lastKey]);
      setSelectedUsers([lastRow]);
    } else {
      setSelectedRowKeys(keys);
      setSelectedUsers(rows);
    }
  }, [mode]);

  // 获取表格数据
  const fetchTableData = useCallback(async (page = 1, pageSize = 10, keywordSearch?: string) => {
    setTableLoading(true);
    try {
      const api = '/sys-user/list';
      const response = await List<ISysUser>(api, { page, pageSize, keywordSearch });
      setDataSource(response.data.data?.data || []);
      setPagination(prev => ({
        ...prev,
        current: page,
        pageSize,
        total: response.data.data?.total || 0,
      }));
    } catch (error) {
      console.error('Failed to fetch users:', error);
      setDataSource([]);
    } finally {
      setTableLoading(false);
    }
  }, []);

  // 打开弹窗时加载数据
  useEffect(() => {
    if (open) {
      fetchTableData(1, pagination.pageSize, tableParams?.keywordSearch);
    }
  }, [open]);

  // 表格配置
  const tableProps: TableProps<ISysUser> = useMemo(() => ({
    columns,
    rowKey: 'id',
    dataSource,
    loading: tableLoading,
    rowSelection: {
      type: mode === 'single' ? 'radio' : 'checkbox',
      selectedRowKeys,
      onChange: handleRowSelectionChange,
      preserveSelectedRowKeys: true,
    },
    pagination: {
      ...pagination,
      showSizeChanger: true,
      showQuickJumper: true,
      onChange: (page, pageSize) => {
        fetchTableData(page, pageSize, tableParams?.keywordSearch);
      },
    },
    bordered: true,
    size: 'small',
    scroll: { x: 800 },
  }), [columns, dataSource, tableLoading, mode, selectedRowKeys, handleRowSelectionChange, pagination, tableParams, fetchTableData]);

  // 处理 Select 的 onChange
  const handleSelectChange = useCallback((selectedValue: number | number[]) => {
    if (mode === 'single') {
      const user = selectedUsers.find(u => u.id === selectedValue);
      if (!user && selectedValue) {
        // 如果选中的用户不在列表中，需要从 API 加载
        List<ISysUser>('/sys-user/list', { id: selectedValue as number })
          .then(res => {
            const loadedUser = res.data.data?.data?.[0];
            if (loadedUser) {
              setSelectedUsers([loadedUser]);
              setSelectedRowKeys([loadedUser.id!]);
            }
          })
          .catch(error => console.error('Failed to load user:', error));
      }
      onChange?.(selectedValue as number || null);
    } else {
      onChange?.(selectedValue as number[]);
    }
  }, [mode, selectedUsers, onChange]);

  // 生成 Select 的 options
  const selectOptions = useMemo(() => {
    return selectedUsers.map(user => ({
      label: user.nickname || user.username,
      value: user.id!,
    }));
  }, [selectedUsers]);

  return (
    <>
      <Select
        mode={mode === 'multiple' ? 'multiple' : undefined}
        value={mode === 'single' 
          ? (selectedUsers[0]?.id || undefined) 
          : selectedUsers.map(u => u.id!)
        }
        onChange={handleSelectChange}
        placeholder={placeholder || t('xinForm.userSelector.placeholder')}
        disabled={disabled}
        loading={loading}
        maxTagCount={maxTagCount}
        open={false}
        onClick={() => !disabled && !readonly && setOpen(true)}
        options={selectOptions}
        style={{ width: '100%' }}
        allowClear
        onClear={() => {
          setSelectedUsers([]);
          setSelectedRowKeys([]);
          onChange?.(mode === 'single' ? null : []);
        }}
      />

      <Modal
        title={t('xinForm.userSelector.modal.title')}
        open={open}
        onCancel={() => setOpen(false)}
        onOk={handleSelect}
        width={1000}
        okText={t('xinForm.userSelector.modal.okText')}
        cancelText={t('xinForm.userSelector.modal.cancelText')}
      >
        <div style={{ marginBottom: 16 }}>
          <Input.Search
            placeholder={t("sysUserList.searchPlaceholder")}
            style={{ width: 304 }}
            onSearch={(value: string) => {
              setParams({ keywordSearch: value });
              fetchTableData(1, pagination.pageSize, value);
            }}
            allowClear
          />
        </div>
        <Table {...tableProps} />
      </Modal>
    </>
  );
};

export default UserSelector;
