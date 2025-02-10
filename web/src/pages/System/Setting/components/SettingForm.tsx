import { FormInstance, message } from 'antd';
import { addApi, editApi } from '@/services/common/table';
import { BetaSchemaForm } from '@ant-design/pro-components';
import React, { ReactElement, useRef } from 'react';
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';

export default (params: {
  settingGroup: any;
  children: ReactElement;
  getSetting: (id: any) => any;
  defaultData?: any;
  id?: any;
}) => {
  const { settingGroup, children, id, defaultData = {}, getSetting } = params;
  const formRef = useRef<FormInstance>();

  /**
   * 设置行
   */
  const columns: ProFormColumnsAndProColumns<any>[] = [
    {
      title: '设置标题',
      dataIndex: 'title',
      valueType: 'text',
      formItemProps: {
        rules: [{
          required: true,
          message: '设置标题必填',
        }],
      },
      colProps: { span: 6 },
    },
    {
      title: '设置Key',
      dataIndex: 'key',
      valueType: 'text',
      formItemProps: {
        rules: [{
          required: true,
          message: '设置Key必填',
        }],
      },
      tooltip: '推荐设置key格式为小写字母和下划线_',
      colProps: { span: 6 },
    },
    {
      title: '设置分组',
      dataIndex: 'group_id',
      valueType: 'select',
      formItemProps: {
        rules: [{
          required: true,
          message: '设置分组必填',
        }],
      },
      fieldProps: {
        options: settingGroup,
        fieldNames: {
          label: 'label',
          value: 'id',
        },
      },
      colProps: { span: 6 },
    },
    {
      title: '设置类型',
      dataIndex: 'type',
      valueType: 'text',
      valueEnum: new Map([
        ['input', '输入框'],
        ['password', '密码框'],
        ['textarea', '文本域'],
        ['number', '数字输入框'],
        ['radio', '单选框'],
        ['rate', '星级组件'],
        ['checkout', '多选框'],
        ['color', '颜色选择器'],
        ['date', '日期选择器'],
        ['time', '时间选择器'],
        ['switch', '开关'],
        ['slider', '滑动输入条'],
        ['select', '选择器'],
      ]),
      formItemProps: {
        rules: [{
          required: true,
          message: '设置类型必填',
        }],
      },
      colProps: { span: 6 },
    },
    {
      title: '设置选项',
      dataIndex: 'options',
      valueType: 'textarea',
      tooltip: '当设置类型为单选框、多选、选择器的时候需要填入设置选项',
      colProps: { span: 12 },
    },
    {
      title: '表单项配置',
      dataIndex: 'props',
      valueType: 'textarea',
      tooltip: '表单项的配置，支持 Ant Design 所有非表达式的值，无需引号，比如：placeholder=Error 或 visibilityToggle=false',
      colProps: { span: 12 },
    },
    {
      title: '设置提示',
      dataIndex: 'describe',
      valueType: 'textarea',
      tooltip: '设置下方的提示信息',
      colProps: { span: 12 },
    },
    {
      title: '排序',
      dataIndex: 'sort',
      valueType: 'digit',
      tooltip: '数字越大越靠前',
      colProps: { span: 12 },
    },
  ];

  return (
    <BetaSchemaForm
      title={id ? '编辑设置项' : '新增设置项'}
      formRef={formRef}
      layoutType={'ModalForm'}
      trigger={children}
      columns={columns}
      initialValues={defaultData}
      grid
      onFinish={async (formData) => {
        if (id) {
          await editApi('/system/setting', { id, ...formData });
          message.success('编辑成功');
        } else {
          await addApi('/system/setting', formData);
          message.success('添加成功');
        }
        await getSetting(formData.group_id)
        return true;
      }}
    />
  );
}
