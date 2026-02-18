import XinTable from '@/components/XinTable';
import {Badge, Button, Tooltip} from 'antd';
import {UnorderedListOutlined} from '@ant-design/icons';
import {type IDict} from '@/domain/iDict';
import type {XinTableColumn} from '@/components/XinTable/typings';
import {useTranslation} from 'react-i18next';
import {useNavigate} from 'react-router';
import dayjs from 'dayjs';
import useDictStore from '@/stores/dict';

/** 字典类型管理 */
export default function DictPage() {
  const {t} = useTranslation();
  const navigate = useNavigate();
  const initDict = useDictStore(state => state.initDict);

  const columns: XinTableColumn<IDict>[] = [
    {
      title: t('dict.id'),
      dataIndex: 'id',
      hideInForm: true,
      width: 80,
      sorter: true,
      align: 'center',
    },
    {
      title: t('dict.name'),
      dataIndex: 'name',
      valueType: 'text',
      colProps: {span: 12},
      rules: [{required: true, message: t('dict.name.required')}],
    },
    {
      title: t('dict.code'),
      dataIndex: 'code',
      valueType: 'text',
      colProps: {span: 12},
      rules: [{required: true, message: t('dict.code.required')}],
    },
    {
      title: t('dict.status'),
      dataIndex: 'status',
      valueType: 'select',
      filters: [
        {text: t('dict.status.normal'), value: 0},
        {text: t('dict.status.disabled'), value: 1},
      ],
      colProps: {span: 12},
      rules: [{required: true, message: t('dict.status.required')}],
      fieldProps: {
        options: [
          {label: t('dict.status.normal'), value: 0},
          {label: t('dict.status.disabled'), value: 1},
        ],
      },
      render: (value: number) => {
        return value === 0
          ? <Badge status="success" text={t('dict.status.normal')}/>
          : <Badge status="error" text={t('dict.status.disabled')}/>;
      }
    },
    {
      title: t('dict.sort'),
      dataIndex: 'sort',
      valueType: 'digit',
      colProps: {span: 12},
      hideInSearch: true,
      fieldProps: {
        min: 0,
        style: {width: '100%'}
      }
    },
    {
      title: t('dict.describe'),
      dataIndex: 'describe',
      valueType: 'textarea',
      colProps: {span: 24},
      hideInSearch: true,
      ellipsis: true,
    },
    {
      title: t('dict.createdAt'),
      dataIndex: 'created_at',
      render: (value: string) => value ? dayjs(value).format('YYYY-MM-DD HH:mm') : '-',
      hideInForm: true,
      hideInSearch: true,
      width: 160,
    },
  ];

  // 刷新字典缓存
  const handleRefreshCache = async () => {
    await initDict();
    window.$message?.success(t('dict.refreshSuccess'));
  };

  // 跳转到字典项页面
  const handleGoToItems = (record: IDict) => {
    navigate(`/system/dict/item?dictId=${record.id}&dictName=${encodeURIComponent(record.name || '')}&dictCode=${record.code}`);
  };

  return (
    <XinTable<IDict>
      api={'/system/dict/list'}
      columns={columns}
      rowKey={'id'}
      accessName={'system.dict.list'}
      searchProps={false}
      formProps={{
        grid: true,
        colProps: {span: 12},
      }}
      toolBarRender={[
        <Button
          type="primary"
          key="refresh"
          onClick={handleRefreshCache}
        >
          {t('dict.refreshCache')}
        </Button>,
      ]}
      operateProps={{
        fixed: 'right',
        width: 180,
      }}
      scroll={{x: 1000}}
      beforeOperateRender={(record) => (
        <Tooltip title={t('dict.manageItems')}>
          <Button
            type="default"
            icon={<UnorderedListOutlined/>}
            size="small"
            onClick={() => handleGoToItems(record)}
          />
        </Tooltip>
      )}
    />
  );
}
