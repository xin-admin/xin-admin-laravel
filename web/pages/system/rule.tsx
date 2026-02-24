import type {ISysRule} from "@/domain/iSysRule.ts";
import {listRule, ruleParent, showRule, statusRule} from "@/api/system/sys_rule.ts";
import {useTranslation} from "react-i18next";
import IconFont from "@/components/IconFont";
import XinTable from "@/components/XinTable";
import type {XinTableColumn, XinTableRef} from "@/components/XinTable/typings.ts";
import {Button, message, Switch, Tag, Tooltip} from "antd";
import {PlusOutlined} from "@ant-design/icons";
import {useEffect, useRef, useState} from "react";
import useAuth from "@/hooks/useAuth.ts";
import AuthButton from "@/components/AuthButton";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import useDictStore from "@/stores/dict";
import DictTag from "@/components/DictTag";

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
  const tableRef = useRef<XinTableRef>(null);
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
          name: t("sysUserRule.parent.0"),
          id: 0,
          children
        }
      ]);
    });
  }, []);

  const columns: XinTableColumn<ISysRule>[] = [
    /** ----------------- 表单使用的 Column ------------------- */
    {
      title: t("sysUserRule.type"),
      dataIndex: 'type',
      valueType: 'radioButton',
      hideInTable: true,
      hideInSearch: true,
      colProps: { span: 24 },
      rules: [{ required: true, message: t("sysUserRule.type.required") }],
      fieldProps: {
        options: getOptions('sys_rule_type'),
      },
    },
    {
      title: t("sysUserRule.parent"),
      dataIndex: 'parent_id',
      hideInTable: true,
      hideInSearch: true,
      valueType: 'treeSelect',
      rules: [{ required: true, message: t("sysUserRule.parent.required") }],
      fieldProps: {
        treeData: parentOptions,
        fieldNames: { label: 'name', value: 'id' },
        disabled: true,
      },
    },
    {
      title: t("sysUserRule.order"),
      hideInTable: true,
      hideInSearch: true,
      dataIndex: 'order',
      valueType: 'digit',
      rules: [{ required: true, message: t("sysUserRule.order.required") }],
    },
    {
      title: t("sysUserRule.name"),
      hideInTable: true,
      hideInSearch: true,
      dataIndex: 'name',
      valueType: 'text',
      rules: [{ required: true, message: t("sysUserRule.name.required") }],
    },
    {
      title: t("sysUserRule.key"),
      valueType: 'text',
      dataIndex: 'key',
      hideInTable: true,
      hideInSearch: true,
      rules: [{ required: true, message: t("sysUserRule.key.required") }],
    },
    {
      title: t("sysUserRule.routePath"),
      dataIndex: 'path',
      valueType: 'text',
      hideInTable: true,
      hideInSearch: true,
      tooltip: t("sysUserRule.routePath.tooltip"),
    },
    {
      title: t("sysUserRule.icon"),
      dataIndex: 'icon',
      valueType: 'icon',
      hideInTable: true,
      hideInSearch: true,
    },
    {
      title: t("sysUserRule.local"),
      dataIndex: 'local',
      valueType: 'text',
      hideInTable: true,
      hideInSearch: true,
    },
    /** ------------------ 表格使用的 Column ---------------- */
    {
      title: t("sysUserRule.name"),
      ellipsis: true,
      hideInForm: true,
      hideInSearch: true,
      dataIndex: 'name',
    },
    {
      ellipsis: true,
      align: 'center',
      title: t("sysUserRule.local.show"),
      dataIndex: 'local',
      hideInForm: true,
      hideInSearch: true,
      render: (data: string) => data ? t(data) : '-'
    },
    {
      title: t("sysUserRule.icon"),
      dataIndex: 'icon',
      align: 'center',
      hideInForm: true,
      hideInSearch: true,
      render: (data: string) => data ? <IconFont name={data} /> : '-'
    },
    {
      title: t("sysUserRule.type"),
      dataIndex: 'type',
      align: 'center',
      hideInForm: true,
      hideInSearch: true,
      render: (value: string) => <DictTag value={value} renderType={'tag'} code={'sys_rule_type'}/>
    },
    {
      title: t("sysUserRule.order"),
      align: 'center',
      dataIndex: 'order',
      hideInForm: true,
      hideInSearch: true,
      render: (value: number) => <Tag variant="filled" color={'purple'}>{value}</Tag>,
    },
    {
      title: t("sysUserRule.key"),
      align: 'center',
      dataIndex: 'key',
      hideInForm: true,
      hideInSearch: true,
      render: (value: string) => <Tag variant="filled" color={'geekblue'}>{value}</Tag>,
    },
    {
      title: t("sysUserRule.hidden"),
      align: 'center',
      dataIndex: 'hidden',
      hideInForm: true,
      hideInSearch: true,
      tooltip: t("sysUserRule.hidden.tooltip"),
      render: (_, data: ISysRule) => {
        if (data.type === 'rule') { return '-' }
        return (
          <Switch
            defaultValue={data.hidden === 1}
            disabled={!auth("system.rule.show")}
            checkedChildren={t("sysUserRule.hidden.1")}
            unCheckedChildren={t("sysUserRule.hidden.0")}
            onChange={ async (_, event) => {
              event.stopPropagation();
              await showRule(data.id!);
              message.success(t("sysUserRule.hidden.updateSuccess"));
            }}
          />
        )
      },
    },
    {
      title: t("sysUserRule.status"),
      dataIndex: 'status',
      hideInForm: true,
      hideInSearch: true,
      tooltip: t("sysUserRule.status.tooltip"),
      align: 'center',
      render: (_, data: ISysRule) => {
        return (
          <Switch
            defaultChecked={data.status === 1}
            disabled={!auth("system.rule.status")}
            checkedChildren={t("sysUserRule.status.1")}
            unCheckedChildren={t("sysUserRule.status.0")}
            onChange={async (_, event) => {
              event.stopPropagation();
              await statusRule(data.id!);
              message.success(t("sysUserRule.status.updateSuccess"));
            }}
          />
        )
      },
    },
    {
      title: t("sysUserRule.created_at"),
      dataIndex: 'created_at',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
    {
      title: t("sysUserRule.updated_at"),
      dataIndex: 'updated_at',
      hideInForm: true,
      hideInSearch: true,
      align: 'center',
      render: (value: string) => value ? dayjs(value).fromNow() : '-',
    },
  ];

  return (
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
      titleRender={t("sysUserRule.title")}
      searchProps={false}
      paginationShow={false}
      scroll={{x: 1200}}
      beforeOperateRender={(data) => (
        <AuthButton auth={"system.rule.create"}>
          <Tooltip title={t("sysUserRule.addChildButton")}>
            <Button
              color={'green'}
              variant={'solid'}
              icon={<PlusOutlined />}
              size={'small'}
              onClick={() => {
                tableRef.current?.form?.()?.setFieldsValue({
                  parent_id: data.id || 0,
                })
                tableRef.current?.form?.()?.open();
              }}
            />
          </Tooltip>
        </AuthButton>
      )}
      formProps={{
        grid: true,
        rowProps: {gutter: [20, 0]},
        colProps: {span: 6}
      }}
      columns={columns}
      api={'/system/rule'}
      rowKey={"id"}
      accessName={"system.rule"}
    />
  )
}

export default Rule;
