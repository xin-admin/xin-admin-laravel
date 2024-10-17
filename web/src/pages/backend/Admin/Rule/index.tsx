import XinTable from '@/components/XinTable';
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import XinDict from '@/components/XinDict';
import { useModel } from '@umijs/max';
import { getRulePid } from '@/services/admin/auth';
import React from 'react';
import { useBoolean } from 'ahooks';
import IconsItem from '@/components/XinForm/IconsItem';
import { message, Switch } from 'antd';
import IconFont from '@/components/IconFont';
import {addApi, editApi} from '@/services/common/table';
import * as AntdIcons from '@ant-design/icons';

const api = '/adminRule';

interface RuleType {
  id?: number;
  pid?: number;
  type?: string | number;
  sort?: number;
  name?: string;
  path?: string;
  icon?: string;
  key?: string;
  local?: string;
  status?: number;
  show?: number;
  created_at?: string;
  updated_at?: string;
}

const Table: React.FC = () => {
  const {getDictionaryData} = useModel('dictModel')
  const allIcons: { [key: string]: any } = AntdIcons;
  const [ref, setRef] = useBoolean();
  const formType = ({ type }: any): any[] => {
    const pid: ProFormColumnsAndProColumns<RuleType> = {
      title: '父节点',
      dataIndex: 'pid',
      valueType: 'treeSelect',
      request: async () => {
        let res = await getRulePid();
        return res.data.data;
      },
      fieldProps: {
        fieldNames: { label: 'name', value: 'id' },
      },
      params: { ref },
      formItemProps: {
        rules: [
          { required: true, message: '此项为必填项' },
        ],
      },
      colProps: { span: 7 },
    };
    const rule: ProFormColumnsAndProColumns<RuleType> = {
      title: '权限标识',
      dataIndex: 'key',
      valueType: 'text',
      tooltip: '例: 路由地址 "/index/index" , 权限标识为 "index.index" , 按钮权限请加上上级路由的权限标识，如：查询按钮权限 "index.index.list" ',
      formItemProps: {
        rules: [
          { required: true, message: '此项为必填项' },
        ],
      },
    };
    const path: ProFormColumnsAndProColumns<RuleType> = {
      title: '路由地址',
      dataIndex: 'path',
      valueType: 'text',
      formItemProps: {
        rules: [
          { required: true, message: '此项为必填项' },
        ],
      },
      tooltip: '项目文件系统路径，忽略：pages 或 pages/backend 或 pages/frontend 或 index.(ts|tsx)',
    };
    const icon: ProFormColumnsAndProColumns<RuleType> = {
      title: '图标',
      dataIndex: 'icon',
      valueType: 'text',
      renderFormItem: (form, config, schema) => <IconsItem dataIndex={form.key} form={schema}
                                                           value={config.value}></IconsItem>,
      colProps: { span: 6 },
    };
    const locale: ProFormColumnsAndProColumns<RuleType> = {
      title: '多语言标识',
      dataIndex: 'local',
      valueType: 'text',
      colProps: { span: 6 },
    };

    if (type === '0') {
      return [path, rule, icon, locale];
    } else if (type === '1') {
      return [pid, path, rule, icon, locale];
    } else if (type === '2') {
      return [pid, rule];
    }
    return [];
  }

  const getIcon = (icon: any): React.ReactNode => {
    if(typeof icon === 'string' && icon) {
      if (icon.startsWith('icon-')) {
        return <IconFont type={icon} className={icon} />
      } else {
        return React.createElement(allIcons[icon]);
      }
    }
    return '-';
  };

  const upDate = (value: boolean, index: string, id: number) => {
    let data: any = { id };
    data[index] = value ? 1 : 0;
    editApi(api + '/edit', data).then(() => {
      message.success('修改成功');
    });
  };

  const columns: ProFormColumnsAndProColumns<RuleType>[] = [
    {
      title: '类型',
      dataIndex: 'type',
      valueType: 'radio',
      request: async () => await getDictionaryData('ruleType'),
      hideInTable: true,
      initialValue: '0',
      formItemProps: {
        rules: [
          { required: true, message: '此项为必填项' },
        ],
      },
      colProps: { span: 10 },
    },
    {
      title: '标题',
      dataIndex: 'name',
      valueType: 'text',
      formItemProps: {
        rules: [
          { required: true, message: '此项为必填项' },
        ],
      },
      width: 200,
      colProps: { span: 7 },
      tooltip: '菜单的标题，可当作菜单栏标题，如果有多语言标识，该项会被覆盖！',
    },
    {
      title: '图标',
      dataIndex: 'icon',
      valueType: 'text',
      renderText: (text, date) => date.icon ? getIcon(text) : '-',
      width: 60,
      align: 'center',
      hideInForm: true,
    },
    {
      valueType: 'dependency',
      name: ['type'],
      hideInTable: true,
      columns: formType,
    },
    {
      title: '类型',
      dataIndex: 'type',
      valueType: 'radioButton',
      request: async () => await getDictionaryData('ruleType'),
      render: (_, date) => <XinDict value={date.type} dict={'ruleType'} />,
      hideInForm: true,
      align: 'center',
      width: 120,
    },
    {
      title: '排序',
      dataIndex: 'sort',
      valueType: 'text',
      tooltip: '数字越大排序越靠前',
      align: 'center',
      width: 100,
      colProps: { span: 4 },
    },
    {
      title: '权限标识',
      dataIndex: 'key',
      valueType: 'text',
      hideInForm: true,
      tooltip: '例: 路由地址 "/index/index" , 权限标识为 "index.index" , 按钮权限请加上上级路由的权限标识，如：查询按钮权限 "index.index.list" ',
      width: 200,
    },
    {
      title: '路由地址',
      dataIndex: 'path',
      valueType: 'text',
      hideInForm: true,
      renderText: (text, record) => record.type !== '2' ? text : '-',
      tooltip: '项目文件系统路径，忽略：pages 或 pages/backend 或 pages/frontend 或 index.(ts|tsx)',
      width: 200,
    },
    {
      title: '显示状态',
      dataIndex: 'show',
      valueType: 'switch',
      tooltip: '菜单栏显示状态，控制菜单是否显示再导航中（菜单规则依然生效）',
      align: 'center',
      width: 120,
      render: (_, data) => {
        if (data.type === '2') {
          return '-';
        }
        return <Switch
          checkedChildren='显示'
          unCheckedChildren='隐藏'
          defaultValue={data.show === 1}
          onChange={(value) => upDate(value, 'show', data.id!)}
        />;
      },
      colProps: { span: 4 },
      hideInForm: true,
    },
    {
      title: '是否禁用',
      dataIndex: 'status',
      valueType: 'switch',
      tooltip: '权限是否禁用（将不会参与权限验证）',
      align: 'center',
      width: 120,
      render: (_, data) => {
        return <Switch
          checkedChildren='启用'
          unCheckedChildren='禁用'
          defaultChecked={data.status === 1}
          onChange={(value) => upDate(value, 'status', data.id!)}
        />;
      },
      colProps: { span: 4 },
      hideInForm: true,
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

  return (
    <XinTable<RuleType>
      tableApi={api}
      columns={columns}
      search={false}
      pagination={false}
      handleAdd={async (date) => {
        let arr = Object.assign(date, {show: 1, status: 1})
        if(date.type === '0') {
          arr.pid = 0
        }
        await addApi(api + '/add', arr );
        return true;
      }}
      optionProps={{
        fixed: 'right',
        width: 100,
        align: "center"
      }}
      handleUpdate={async (date) => {
        let arr: RuleType = {};
        if(date.type === '0') {
          arr = {
            id: date.id,
            pid: 0,
            type: 0,
            sort: date.sort,
            name: date.name,
            path: date.path,
            icon: date.icon,
            key: date.key,
            local: date.local
          }
        }else if(date.type === '1') {
          arr = {
            id: date.id,
            pid: date.pid,
            type: 1,
            sort: date.sort,
            name: date.name,
            path: date.path,
            icon: date.icon,
            key: date.key,
            local: date.local
          }
        } else if(date.type === '2') {
          arr = {
            id: date.id,
            pid: date.pid,
            type: 2,
            sort: date.sort,
            name: date.name,
            key: date.key,
            path: '',
            local: '',
            icon: ''
          }
        }else {
          message.warning('类型错误！');
          return false;
        }
        await editApi(api + '/edit', arr );
        message.success('更新成功！');
        return true
      }}
      addBefore={() => setRef.toggle()}
      accessName={'admin.rule'}
      scroll={{ x: 1480 }}
    />
  )
}

export default Table
