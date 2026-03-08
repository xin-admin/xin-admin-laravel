import XinTable from '@/components/XinTable';
import {Badge, Button, Space, Tag, Typography} from 'antd';
import type {IDictItem} from '@/domain/iDictItem';
import {COLORS} from '@/domain/iDictItem';
import type {XinTableColumn} from '@/components/XinTable/typings';
import {useEffect, useState} from 'react';
import {useTranslation} from 'react-i18next';
import {useNavigate, useSearchParams} from 'react-router';
import {Create, Update} from "@/api/common/table.ts";
import dayjs from 'dayjs';
import {LeftOutlined} from "@ant-design/icons";

/** 字典数据管理 */
export default function DictItemPage() {
  const {t} = useTranslation();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();

  // 从 URL 参数获取字典信息
  const dictId = searchParams.get('dictId');
  const dictName = searchParams.get('dictName') || '';
  const dictCode = searchParams.get('dictCode') || '';

  const [currentDict, setCurrentDict] = useState({
    id: dictId ? parseInt(dictId) : 0,
    name: decodeURIComponent(dictName),
    code: dictCode,
  });

  // 监听 URL 参数变化
  useEffect(() => {
    if (dictId) {
      setCurrentDict({
        id: parseInt(dictId),
        name: decodeURIComponent(dictName),
        code: dictCode,
      });
    }
  }, [dictId, dictName, dictCode]);

  const columns: XinTableColumn<IDictItem>[] = [
    {
      title: t('dictItem.id'),
      dataIndex: 'id',
      hideInForm: true,
      width: 80,
      align: 'center',
    },
    {
      title: t('dictItem.label'),
      dataIndex: 'label',
      valueType: 'text',
      rules: [{required: true, message: t('dictItem.label.required')}],
    },
    {
      title: t('dictItem.value'),
      dataIndex: 'value',
      valueType: 'text',
      rules: [{required: true, message: t('dictItem.value.required')}],
    },
    {
      title: t('dictItem.color'),
      dataIndex: 'color',
      valueType: 'select',
      colProps: {span: 12},
      initialValue: 'default',
      fieldProps: {
        options: COLORS.map(item => ({
          label: <Tag color={item.value}>{item.label}</Tag>,
          value: item.value
        })),
      },
      render: (value: string) => {
        const item = COLORS.find(i => i.value === value);
        return <Tag color={value}>{item?.label || value}</Tag>;
      }
    },
    {
      title: t('dictItem.isDefault'),
      dataIndex: 'is_default',
      valueType: 'select',
      colProps: {span: 12},
      initialValue: 0,
      rules: [{required: true, message: t('dictItem.isDefault.required')}],
      fieldProps: {
        options: [
          {label: t('dictItem.isDefault.yes'), value: 1},
          {label: t('dictItem.isDefault.no'), value: 0},
        ],
      },
      render: (value: number) => {
        return value === 1
          ? <Tag color="blue">{t('dictItem.isDefault.yes')}</Tag>
          : t('dictItem.isDefault.no');
      }
    },
    {
      title: t('dictItem.sort'),
      dataIndex: 'sort',
      valueType: 'digit',
      colProps: {span: 12},
      hideInSearch: true,
      initialValue: 0,
      fieldProps: {
        min: 0,
        style: {width: '100%'}
      }
    },
    {
      title: t('dictItem.status'),
      dataIndex: 'status',
      valueType: 'select',
      colProps: {span: 12},
      initialValue: 0,
      rules: [{required: true, message: t('dictItem.status.required')}],
      fieldProps: {
        options: [
          {label: t('dictItem.status.normal'), value: 0},
          {label: t('dictItem.status.disabled'), value: 1},
        ],
      },
      render: (value: number) => {
        return value === 0
          ? <Badge status="success" text={t('dictItem.status.normal')}/>
          : <Badge status="error" text={t('dictItem.status.disabled')}/>;
      }
    },
    {
      title: t('dictItem.createTime'),
      dataIndex: 'created_at',
      render: (value: string) => value ? dayjs(value).format('YYYY-MM-DD HH:mm') : '-',
      hideInForm: true,
      hideInSearch: true,
      width: 160,
    },
  ];

  // 返回字典列表
  const handleGoBack = () => {
    navigate('/system/dict');
  };

  // 如果没有字典ID，显示提示
  if (!currentDict.id) {
    return (
      <div style={{padding: 50, textAlign: 'center'}}>
        <div style={{marginTop: 50, color: '#999'}}>
          {t('dict.selectDictFirst')}
        </div>
      </div>
    );
  }

  return (
    <Space orientation={'vertical'} style={{width: '100%'}}>
      <div>
        <Button type={'link'} onClick={handleGoBack} icon={<LeftOutlined/>} classNames={{ root: 'p-0 mb-2' }}>
          {t('dict.backToList')}
        </Button>
        <Typography.Title level={3}>
          <span className={'mr-2'}>{t('dict.itemManagement')} - {currentDict.name}</span>
          <Typography.Text type="secondary">{currentDict.code}</Typography.Text>
        </Typography.Title>

      </div>
      <XinTable<IDictItem>
        api={'/system/dict/item'}
        columns={columns}
        rowKey={'id'}
        accessName={'system.dict.item'}
        formProps={{
          grid: true,
          colProps: {span: 12}
        }}
        requestParams={(params) => {
          return {
            ...params,
            dict_id: currentDict.id,
          }
        }}
        searchShow={false}
        handleFinish={async (values, mode, _form, defaultValue) => {
          if (mode === 'create') {
            await Create('/system/dict/item', {
              ...values,
              dict_id: currentDict.id,
            });
            window.$message?.success(t('dict.item.createSuccess'));
          } else {
            await Update('/system/dict/item/' + defaultValue?.id, {
              ...values,
              dict_id: currentDict.id,
            });
            window.$message?.success(t('dict.item.updateSuccess'));
          }
          return true;
        }}
      />
    </Space>
  );
}
