import XinTableV2 from '@/components/XinTableV2';
import { Button, Col, Empty, Row, Tag } from 'antd';
import type { IDict } from '@/domain/iDict';
import type { IDictItem } from '@/domain/iDictItem';
import type { XinTableColumn, XinTableV2Ref } from '@/components/XinTableV2/typings';
import { useEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import createAxios from '@/utils/request';
import dayjs from 'dayjs';
import {Create, Update} from "@/api/common/table.ts";

/** 字典管理 */
export default () => {
  const { t } = useTranslation();

  // 字典
  const columns: XinTableColumn<IDict>[] = [
    { 
      title: t('dict.id'), 
      dataIndex: 'id',
      hideInForm: true,
      width: 100,
      sorter: true,
      align: 'center',
    },
    { 
      title: t('dict.name'),
      dataIndex: 'name', 
      valueType: 'text', 
      colProps: { span: 8 },
      rules: [{ required: true, message: t('dict.name.required') }],
    },
    {
      title: t('dict.code'), 
      dataIndex: 'code', 
      valueType: 'text', 
      colProps: { span: 8 },
      rules: [{ required: true, message: t('dict.code.required') }],
    },
    {
      title: t('dict.type'), 
      dataIndex: 'type', 
      valueType: 'select', 
      filters: [
        { text: t('dict.type.default'), value: 'default' },
        { text: t('dict.type.badge'), value: 'badge' },
        { text: t('dict.type.tag'), value: 'tag' },
      ], 
      colProps: { span: 8 },
      rules: [{ required: true, message: t('dict.type.required') }],
      fieldProps: {
        options: [
          { label: t('dict.type.default'), value: 'default' },
          { label: t('dict.type.badge'), value: 'badge' },
          { label: t('dict.type.tag'), value: 'tag' },
        ],
      },
      render: (value: string) => {
        const map: Record<string, string> = {
          default: t('dict.type.default'),
          badge: t('dict.type.badge'),
          tag: t('dict.type.tag'),
        };
        return map[value] || value;
      }
    },
    { 
      title: t('dict.describe'), 
      dataIndex: 'describe', 
      valueType: 'textarea', 
      colProps: { span: 24 }, 
      hideInSearch: true,
      ellipsis: true,
    },
    { 
      title: t('dict.createdAt'), 
      dataIndex: 'created_at',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
      hideInForm: true,
      hideInSearch: true,
    },
    { 
      title: t('dict.updatedAt'), 
      dataIndex: 'updated_at',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
      hideInForm: true,
      hideInSearch: true,
    },
  ];

  // 字典项
  const itemColumns: XinTableColumn<IDictItem>[] = [
    { 
      title: t('dictItem.id'), 
      dataIndex: 'id', 
      hideInForm: true, 
      hideInTable: true 
    },
    { 
      title: t('dictItem.label'), 
      dataIndex: 'label', 
      valueType: 'text', 
      rules: [{ required: true, message: t('dictItem.label.required') }],
    },
    { 
      title: t('dictItem.value'), 
      dataIndex: 'value', 
      valueType: 'text', 
      rules: [{ required: true, message: t('dictItem.value.required') }],
    },
    {
      title: t('dictItem.status'), 
      dataIndex: 'status', 
      valueType: 'select',
      rules: [{ required: true, message: t('dictItem.status.required') }],
      fieldProps: {
        options: [
          { label: t('dictItem.status.success'), value: 'success' },
          { label: t('dictItem.status.error'), value: 'error' },
          { label: t('dictItem.status.default'), value: 'default' },
          { label: t('dictItem.status.processing'), value: 'processing' },
          { label: t('dictItem.status.warning'), value: 'warning' },
        ],
      },
      render: (value: string) => {
        const colorMap: Record<string, string> = {
          success: 'success',
          error: 'error',
          default: 'default',
          processing: 'processing',
          warning: 'warning',
        };
        return <Tag color={colorMap[value] || 'default'}>{value}</Tag>;
      }
    },
    { 
      title: t('dictItem.switch'), 
      dataIndex: 'switch', 
      valueType: 'switch', 
      rules: [{ required: true, message: t('dictItem.switch.required') }],
      render: (value: number) => {
        return value === 1 ? t('dictItem.switch.on') : t('dictItem.switch.off');
      }
    },
    { 
      title: t('dictItem.createTime'), 
      dataIndex: 'created_at',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
      hideInForm: true, 
      hideInTable: true,
      hideInSearch: true,
    },
    { 
      title: t('dictItem.updateTime'), 
      dataIndex: 'updated_at',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
      hideInForm: true, 
      hideInTable: true,
      hideInSearch: true,
    },
  ];

  const [selectedRows, setSelectedRows] = useState<IDict>();
  const [refreshLoading, setRefreshLoading] = useState(false);
  const tableRef = useRef<XinTableV2Ref<IDictItem>>(null!);

  // 刷新字典缓存
  const handleRefreshCache = async () => {
    try {
      setRefreshLoading(true);
      await createAxios({
        url: '/sys/dict/refresh',
        method: 'post',
      });
      window.$message?.success(t('dict.refreshSuccess'));
    } catch {
      // 错误已在 request 中处理
    } finally {
      setRefreshLoading(false);
    }
  };

  // 当选中字典变化时，刷新字典项表格
  useEffect(() => {
    if (selectedRows && tableRef.current) {
      tableRef.current.reload();
    }
  }, [selectedRows?.id]);

  return (
    <Row gutter={20}>
      <Col span={14}>
        <XinTableV2<IDict>
          api={'/sys/dict/list'}
          columns={columns}
          rowKey={'id'}
          accessName={'system.dict.list'}
          searchProps={false}
          formProps={{
            grid: true,
            colProps: { span: 12 },
          }}
          toolBarRender={[
            <Button 
              type="primary" 
              key="refresh" 
              loading={refreshLoading}
              onClick={handleRefreshCache}
            >
              {t('dict.refreshCache')}
            </Button>,
          ]}
          operateProps={{
            fixed: 'right'
          }}
          scroll={{x: 1000}}
          rowSelection={{
            type: 'radio',
            onChange: (_, rows) => setSelectedRows(rows[0])
          }}
        />
      </Col>
      <Col span={10}>
        {selectedRows ? (
          <XinTableV2<IDictItem>
            api={'/sys/dict/item'}
            columns={itemColumns}
            rowKey={'id'}
            titleRender={<span>{`${t('dict.itemManagement')}（${selectedRows?.name}）`}</span>}
            accessName={'system.dict.item'}
            formProps={{ 
              grid: true, 
              colProps: { span: 12 }
            }}
            tableRef={tableRef}
            requestParams={(params) => ({
              ...params,
              dict_id: selectedRows.id,
            })}
            searchShow={false}
            handleFinish={async (values, mode, _form, defaultValue) => {
              if (mode === 'create') {
                await Create('/sys/dict/item', {
                  ...values,
                  dict_id: selectedRows.id,
                });
                window.$message?.success(t('dict.item.createSuccess'));
              } else {
                await Update('/sys/dict/item/' + defaultValue?.id, {
                  ...values,
                  dict_id: selectedRows.id,
                });
                window.$message?.success(t('dict.item.updateSuccess'));
              }
              return true;
            }}
          />
        ) : (
          <Empty description={t('dict.selectDict')} />
        )}
      </Col>
    </Row>
  );
}
