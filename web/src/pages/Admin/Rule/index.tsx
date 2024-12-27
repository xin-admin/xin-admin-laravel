import XinTableV2 from '@/components/XinTableV2';
import { XinTableColumn, XinTableRef } from '@/components/XinTableV2/typings';
import XinDict from '@/components/XinDict';
import { useModel } from '@umijs/max';
import React, { useRef, useState } from 'react';
import IconsItem from '@/components/XinForm/IconsItem';
import { message, Switch } from 'antd';
import IconFont from '@/components/IconFont';
import {addApi, editApi} from '@/services/common/table';
import { show as showApi, status as statusApi, getRuleParent } from '@/services/adminRule';
import { IRule } from '@/domain/rule';
import { RedoOutlined } from '@ant-design/icons';

export default () => {
  // 字典
  const dictEnum = useModel('dictModel', ({dictEnum}) => dictEnum)
  const tableRef = useRef<XinTableRef>();
  // 菜单项
  const parentItem: XinTableColumn<IRule> = {
    title: '父节点',
    dataIndex: 'parent_id',
    valueType: 'treeSelect',
    request: async () => {
      let data = await getRuleParent();
      return data.data.data
    },
    fieldProps: { fieldNames: { label: 'name', value: 'rule_id' }},
    formItemProps: { rules: [{ required: true, message: '此项为必填项' }]},
  };
  const pathItem: XinTableColumn<IRule> = {
    title: '路由地址',
    dataIndex: 'path',
    valueType: 'text',
    formItemProps: { rules: [{ required: true, message: '此项为必填项' }]},
    tooltip: '项目文件系统路径，忽略：pages 或 index.(ts|tsx)',
  };
  const iconItem: XinTableColumn<IRule> = {
    title: '图标',
    dataIndex: 'icon',
    valueType: 'text',
    renderFormItem: (form, config, schema) => <IconsItem dataIndex={form.key} form={schema} value={config.value}></IconsItem>,
  };
  const localeItem: XinTableColumn<IRule> = {
    title: '多语言标识',
    dataIndex: 'local',
    valueType: 'text',
  };

  const columns: XinTableColumn<IRule>[] = [
    {
      title: '类型',
      dataIndex: 'type',
      valueType: 'radio',
      valueEnum: dictEnum.get('ruleType'),
      hideInTable: true,
      initialValue: '0',
      formItemProps: { rules: [{ required: true, message: '此项为必填项' }]},
    },
    {
      title: '标题',
      dataIndex: 'name',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '此项为必填项' }]},
      tooltip: '菜单的标题，可当作菜单栏标题，如果有多语言标识，该项会被覆盖！',
    },
    {
      title: '图标',
      dataIndex: 'icon',
      valueType: 'text',
      renderText: (_, date) => date.icon ? <IconFont name={date.icon}/> : '-',
      align: 'center',
      hideInForm: true,
    },
    {
      valueType: 'dependency',
      name: ['type'],
      hideInTable: true,
      columns: ({ type }: any): any[] => {
        if (type === '0') {
          return [pathItem, iconItem, localeItem];
        } else if (type === '1') {
          return [parentItem, pathItem, iconItem, localeItem];
        } else {
          return [parentItem];
        }
      },
    },
    {
      title: '类型',
      dataIndex: 'type',
      valueType: 'radioButton',
      valueEnum: dictEnum.get('ruleType'),
      render: (_, date) => <XinDict value={date.type} dict={'ruleType'} />,
      hideInForm: true,
      align: 'center',
    },
    {
      title: '权限标识',
      dataIndex: 'key',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '此项为必填项' }]},
      tooltip: '例: 路由地址 "/index/index" , 权限标识为 "index.index" , 按钮权限请加上上级路由的权限标识，如：查询按钮权限 "index.index.list" ',
    },
    {
      title: '排序',
      dataIndex: 'sort',
      valueType: 'digit',
      tooltip: '数字越大排序越靠前',
      align: 'center',
      colProps: { span: 6 }
    },
    {
      title: '路由地址',
      dataIndex: 'path',
      valueType: 'text',
      hideInForm: true,
      renderText: (text, record) => record.type !== '2' ? text : '-',
      tooltip: '项目文件系统路径，忽略：pages 或 index.(ts|tsx)',
    },
    {
      title: '显示状态',
      dataIndex: 'show',
      valueType: 'switch',
      tooltip: '菜单栏显示状态，控制菜单是否显示再导航中（菜单规则依然生效）',
      align: 'center',
      colProps: { span: 6 },
      render: (_, data) => {
        if (data.type === '2') { return '-' }
        return (
          <Switch
            checkedChildren='显示'
            unCheckedChildren='隐藏'
            defaultValue={data.show === 1}
            onChange={ async () => {
              await showApi(data.rule_id)
              message.success('修改成功')
            }}
          />
        )
      },
    },
    {
      title: '是否禁用',
      dataIndex: 'status',
      valueType: 'switch',
      tooltip: '权限是否禁用（将不会参与权限验证）',
      align: 'center',
      colProps: { span: 6 },
      render: (_, data) => {
        return (
          <Switch
            checkedChildren='启用'
            unCheckedChildren='禁用'
            defaultChecked={data.status === 1}
            onChange={async () => {
              await statusApi(data.rule_id)
              message.success('修改成功')
            }}
          />
        )
      },
    },
    {
      title: '创建时间',
      dataIndex: 'created_at',
      valueType: 'date',
      hideInForm: true,
      align: 'center',
    },
    {
      title: '最近修改',
      dataIndex: 'updated_at',
      valueType: 'fromNow',
      hideInForm: true,
      align: 'center',
    },
  ];

  /**
   * 添加菜单
   * @param ruleData
   */
  const handleAdd = async (ruleData: IRule) => {
    let data = Object.assign(ruleData, {show: 1, status: 1})
    if(data.type === '0') { data.pid = 0 }
    await addApi('/admin/rule', data);
    return true;
  }

  /**
   * 编辑菜单
   * @param ruleData
   */
  const handleUpdate = async (ruleData: IRule) => {
    let data: IRule = {};
    if (ruleData.type === '0') {
      data = Object.assign(ruleData, { pid: 0, type: 0})
    } else if (ruleData.type === '1') {
      data = Object.assign(ruleData, { type: 1 })
    } else if (ruleData.type === '2') {
      data = Object.assign(ruleData, { type: 2, path: '', local: '', icon: '' })
    } else {
      message.warning('类型错误！');
      return false;
    }
    await editApi('/admin/rule' , data );
    message.success('更新成功！');
    return true
  }

  const [params, setParams] = useState<any>();

  return (
    <XinTableV2
      columns={columns}
      api={'/admin/rule'}
      accessName={'admin.rule'}
      rowKey={'rule_id'}
      ref={tableRef}
      tableProps={{
        rowSelection: { type: 'checkbox' },
        params: params,
        cardProps: { bordered: true },
        search: false,
        headerTitle:'权限列表',
        toolbar: { settings: [
            {
              icon: <RedoOutlined />,
              onClick: () => {
                console.log("刷新表格");
                console.log(tableRef.current);
                tableRef.current?.tableRef?.current?.reload?.()
              },
              tooltip: "刷新表格"
            }
        ]}
      }}
      formProps={{
        grid: true,
        colProps: { span: 12 },
      }}
    />
  )
}
