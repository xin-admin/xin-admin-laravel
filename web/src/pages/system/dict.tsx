import XinTable from '@/components/XinTable';
import {Badge, Button, Drawer, Tag, Tooltip, Typography} from 'antd';
import {type IDict} from '@/domain/iDict.ts';
import type {XinTableColumn} from '@/components/XinTable/typings.ts';
import {useTranslation} from 'react-i18next';
import dayjs from 'dayjs';
import useDictStore from '@/stores/dict';
import {useState} from "react";
import {COLORS, type IDictItem} from "@/domain/iDictItem.ts";
import {Create, Update} from "@/api/common/table.ts";

const { Title, Text } = Typography;

/** 字典类型管理 */
export default function DictPage() {
  const {t} = useTranslation();
  const initDict = useDictStore(state => state.initDict);
  const [openItem, setOpenItem] = useState(false);
  const [openId, setOpenId] = useState<number | null>(null);

  const columns: XinTableColumn<IDict>[] = [
    {
      title: t('system.dict.name'),
      dataIndex: 'name',
      valueType: 'text',
      colProps: {span: 12},
      align: 'center',
      width: 300,
      rules: [{required: true, message: t('system.dict.name.required')}],
    },
    {
      title: t('system.dict.code'),
      dataIndex: 'code',
      valueType: 'text',
      colProps: {span: 12},
      align: 'center',
      width: 300,
      rules: [{required: true, message: t('system.dict.code.required')}],
    },
    {
      title: t('system.dict.describe'),
      dataIndex: 'describe',
      valueType: 'textarea',
      colProps: {span: 24},
      align: 'center',
      hideInSearch: true,
      ellipsis: true,
    },
    {
      title: t('system.dict.createdAt'),
      dataIndex: 'created_at',
      render: (value: string) => value ? dayjs(value).format('YYYY-MM-DD HH:mm') : '-',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      width: 180,
    },
    {
      title: t('system.dict.updatedAt'),
      dataIndex: 'updated_at',
      render: (value: string) => value ? dayjs(value).format('YYYY-MM-DD HH:mm') : '-',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      width: 180,
    },
  ];

  const itemColumns: XinTableColumn<IDictItem>[] = [
    {
      title: t('system.dict.item.label'),
      dataIndex: 'label',
      valueType: 'text',
      rules: [{required: true, message: t('system.dict.item.label.required')}],
    },
    {
      title: t('system.dict.item.value'),
      dataIndex: 'value',
      valueType: 'text',
      rules: [{required: true, message: t('system.dict.item.value.required')}],
    },
    {
      title: t('system.dict.item.color'),
      dataIndex: 'color',
      valueType: 'radioButton',
      colProps: {span: 24},
      initialValue: 'default',
      fieldProps: {
        options: COLORS.map(item => ({
          label: <Tag color={item.value}>{item.label}</Tag>,
          value: item.value
        })),
      },
      render: (value: string) => {
        const item = COLORS.find(i => i.value === value);
        return <Tag color={value} >{item?.label || value}</Tag>;
      }
    },
    {
      title: t('system.dict.item.sort'),
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
      title: t('system.dict.item.status'),
      dataIndex: 'status',
      valueType: 'radio',
      colProps: {span: 12},
      initialValue: 0,
      rules: [{required: true, message: t('system.dict.item.status.required')}],
      fieldProps: {
        options: [
          {label: t('system.dict.item.status.normal'), value: 0},
          {label: t('system.dict.item.status.disabled'), value: 1},
        ],
      },
      render: (value: number) => {
        return value === 0
            ? <Badge status="success" text={t('system.dict.item.status.normal')}/>
            : <Badge status="error" text={t('system.dict.item.status.disabled')}/>;
      }
    },
    {
      title: t('system.dict.item.createTime'),
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
    window.$message?.success(t('system.dict.refreshSuccess'));
  };

  // 跳转到字典项页面
  const handleOpenItems = (record: IDict) => {
    setOpenId(record.id!);
    setOpenItem(true);
  };

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>{t('system.dict.page.title')}</Title>
        <Text type="secondary">{t('system.dict.page.description')}</Text>
      </div>
      <XinTable<IDict>
      api={'/system/dict/list'}
      columns={columns}
      rowKey={'id'}
      accessName={'system.dict.list'}
      searchProps={false}
      formProps={{
        grid: true,
        colProps: {span: 12},
        rowProps: {gutter: [30, 0]},
        layout: 'vertical'
      }}
      modalProps={{ width: 800 }}
      actionBarRender={(dom) => [
        dom.add,
        <Button
          type="primary"
          key="refresh"
          onClick={handleRefreshCache}
        >
          {t('system.dict.refreshCache')}
        </Button>,
        dom.keywordSearch,
      ]}
      operateProps={{
        fixed: 'right',
        width: 180,
      }}
      scroll={{x: 1000}}
      operateRender={(record, dom) => [
        <Tooltip title={t('system.dict.manageItems')}>
          <Button
            color="primary"
            size="small"
            variant="solid"
            onClick={() => handleOpenItems(record)}
          >
            {t('system.dict.manageItems')}
          </Button>
        </Tooltip>,
        dom.edit,
        dom.del
      ]}
    />
    <Drawer
      title={t('system.dict.manageItems')}
      placement={'right'}
      size={800}
      closable={false}
      destroyOnHidden={true}
      onClose={() => { setOpenItem(false) }}
      open={openItem}
    >
      <XinTable<IDictItem>
        api={'/system/dict/item'}
        columns={itemColumns}
        rowKey={'id'}
        accessName={'system.dict.item'}
        formProps={{
          grid: true,
          colProps: {span: 12},
          layout: 'vertical',
          rowProps: {gutter: [30, 0]}
        }}
        cardProps={{
          styles: {body: { padding: 0 }, root: { border: 0 }},
        }}
        modalProps={{ width: 600 }}
        requestParams={(params) => {
          return {
            ...params,
            dict_id: openId,
          }
        }}
        searchShow={false}
        handleFinish={async (values, mode, _form, defaultValue) => {
          if (mode === 'create') {
            await Create('/system/dict/item', {
              ...values,
              dict_id: openId,
            });
            window.$message?.success(t('system.dict.item.createSuccess'));
          } else {
            await Update('/system/dict/item/' + defaultValue?.id, {
              ...values,
              dict_id: openId,
            });
            window.$message?.success(t('system.dict.item.updateSuccess'));
          }
          return true;
        }}
      />
    </Drawer>
  </>
  );
}
