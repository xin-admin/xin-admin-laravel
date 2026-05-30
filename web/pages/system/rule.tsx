import type {ISysRule} from "@/domain/iSysRule.ts";
import {listRule, ruleParent, showRule, statusRule} from "@/api/system/sys_rule.ts";
import {useTranslation} from "react-i18next";
import IconFont from "@/components/IconFont";
import XinTable from "@/components/XinTable";
import type {XinTableColumn, XinTableInstance} from "@/components/XinTable/typings.ts";
import {message, Switch, Tag, Typography} from "antd";
import {useEffect, useRef, useState} from "react";
import useAuth from "@/hooks/useAuth.ts";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import useDictStore from "@/stores/dict";
import DictTag from "@/components/DictTag";

const { Title, Text } = Typography;

dayjs.extend(relativeTime);

interface RuleParent {
  id: number;
  name: string;
  children?: RuleParent[];
}

const Rule =  () => {
  const {t} = useTranslation();
  const {auth} = useAuth();
  const [parentOptions, setParentOptions] = useState<RuleParent[]>([]);
  const tableRef = useRef<XinTableInstance<ISysRule>>(null);
  const getOptions = useDictStore(state => state.getOptions);

  // 加载父级选项
  useEffect(() => {
    ruleParent().then(res => {
      const children = (res.data.data || []).map((item: any) => ({
        id: item.id,
        name: item.name,
        children: item.children
      }));
      setParentOptions([
        {
          name: t("system.rule.parent.0"),
          id: 0,
          children
        }
      ]);
    });
  }, []);

  const columns: XinTableColumn<ISysRule>[] = [
    /** ----------------- 表单使用的 Column ------------------- */
    {
      title: t("system.rule.type"),
      dataIndex: 'type',
      valueType: 'radioButton',
      hideInTable: true,
      hideInSearch: true,
      colProps: { span: 24 },
      rules: [{ required: true, message: t("system.rule.type.required") }],
      fieldProps: {
        options: getOptions('sys_rule_type'),
      },
    },
    {
      title: t("system.rule.parent"),
      dataIndex: 'parent_id',
      hideInTable: true,
      hideInSearch: true,
      valueType: 'treeSelect',
      rules: [{ required: true, message: t("system.rule.parent.required") }],
      fieldProps: {
        treeData: parentOptions,
        fieldNames: { label: 'name', value: 'id' },
      },
    },
    {
      title: t("system.rule.order"),
      hideInTable: true,
      hideInSearch: true,
      dataIndex: 'order',
      valueType: 'digit',
      rules: [{ required: true, message: t("system.rule.order.required") }],
    },
    {
      title: t("system.rule.name"),
      hideInTable: true,
      hideInSearch: true,
      dataIndex: 'name',
      valueType: 'text',
      rules: [{ required: true, message: t("system.rule.name.required") }],
    },
    {
      title: t("system.rule.key"),
      valueType: 'text',
      dataIndex: 'key',
      hideInTable: true,
      hideInSearch: true,
      rules: [{ required: true, message: t("system.rule.key.required") }],
    },
    {
      title: t("system.rule.link"),
      dataIndex: 'link',
      valueType: 'radio',
      hideInTable: true,
      hideInSearch: true,
      dependency: {
        dependencies: ['type'],
        visible: values => values.type === 'route'
      },
      fieldProps: {
        options: [
          {label: t("system.rule.link.0"), value: 0},
          {label: t("system.rule.link.1"), value: 1},
        ],
      },
    },
    {
      title: t("system.rule.routePath"),
      dataIndex: 'path',
      valueType: 'text',
      hideInTable: true,
      hideInSearch: true,
      tooltip: t("system.rule.routePath.tooltip"),
      dependency: {
        dependencies: ['type'],
        visible: values => values.type === 'route'
      },
    },
    {
      title: t("system.rule.icon"),
      dataIndex: 'icon',
      valueType: 'icon',
      hideInTable: true,
      hideInSearch: true,
      dependency: {
        dependencies: ['type'],
        visible: values => values.type === 'route' || values.type === 'menu'
      },
    },
    {
      title: t("system.rule.local"),
      dataIndex: 'local',
      valueType: 'text',
      hideInTable: true,
      hideInSearch: true,
      dependency: {
        dependencies: ['type'],
        visible: values => values.type === 'route' || values.type === 'menu'
      },
    },
    /** ------------------ 表格使用的 Column ---------------- */
    {
      title: t("system.rule.name"),
      ellipsis: true,
      hideInForm: true,
      hideInSearch: true,
      dataIndex: 'name',
    },
    {
      ellipsis: true,
      align: 'center',
      title: t("system.rule.local.show"),
      dataIndex: 'local',
      hideInForm: true,
      hideInSearch: true,
      render: (data: string) => data ? t(data) : '-'
    },
    {
      title: t("system.rule.icon"),
      dataIndex: 'icon',
      align: 'center',
      hideInForm: true,
      hideInSearch: true,
      render: (data: string) => data ? <IconFont name={data} /> : '-'
    },
    {
      title: t("system.rule.type"),
      dataIndex: 'type',
      align: 'center',
      hideInForm: true,
      hideInSearch: true,
      render: (value: string) => <DictTag value={value} renderType={'tag'} code={'sys_rule_type'}/>
    },
    {
      title: t("system.rule.order"),
      align: 'center',
      dataIndex: 'order',
      hideInForm: true,
      hideInSearch: true,
      render: (value: number) => <Tag variant="filled" color={'purple'}>{value}</Tag>,
    },
    {
      title: t("system.rule.key"),
      align: 'center',
      dataIndex: 'key',
      hideInForm: true,
      hideInSearch: true,
      render: (value: string) => <Tag variant="filled" color={'geekblue'}>{value}</Tag>,
    },
    {
      title: t("system.rule.hidden"),
      align: 'center',
      dataIndex: 'hidden',
      hideInForm: true,
      hideInSearch: true,
      tooltip: t("system.rule.hidden.tooltip"),
      render: (_, data: ISysRule) => {
        if (data.type === 'rule') { return '-' }
        return (
          <Switch
            defaultValue={data.hidden === 1}
            disabled={!auth("system.rule.show")}
            checkedChildren={t("system.rule.hidden.1")}
            unCheckedChildren={t("system.rule.hidden.0")}
            onChange={ async (_, event) => {
              event.stopPropagation();
              await showRule(data.id!);
              message.success(t("system.rule.hidden.updateSuccess"));
            }}
          />
        )
      },
    },
    {
      title: t("system.rule.status"),
      dataIndex: 'status',
      hideInForm: true,
      hideInSearch: true,
      tooltip: t("system.rule.status.tooltip"),
      align: 'center',
      render: (_, data: ISysRule) => {
        return (
          <Switch
            defaultChecked={data.status === 1}
            disabled={!auth("system.rule.status")}
            checkedChildren={t("system.rule.status.1")}
            unCheckedChildren={t("system.rule.status.0")}
            onChange={async (_, event) => {
              event.stopPropagation();
              await statusRule(data.id!);
              message.success(t("system.rule.status.updateSuccess"));
            }}
          />
        )
      },
    },
    {
      title: t("system.rule.created_at"),
      dataIndex: 'created_at',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
    {
      title: t("system.rule.updated_at"),
      dataIndex: 'updated_at',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
  ];

  return (
    <>
      <div className={'mb-5'}>
        <Title level={3}>{t("system.rule.title")}</Title>
        <Text type="secondary">{t("system.rule.description")}</Text>
      </div>
      <XinTable<ISysRule>
        handleRequest={async () => {
          const { data } = await listRule();
          return {
            data: data.data || [],
            total: data.data?.length || 0
          };
        }}
        tableRef={tableRef}
        searchShow={false}
        searchProps={false}
        paginationShow={false}
        scroll={{x: 1200}}
        formProps={{
          grid: true,
          rowProps: {gutter: [20, 0]},
          colProps: {span: 12},
          layout: 'vertical'
        }}
        modalProps={{ width: 800 }}
        columns={columns}
        api={'/system/rule'}
        rowKey={"id"}
        accessName={"system.rule"}
      />
    </>
  )
}

export default Rule;
