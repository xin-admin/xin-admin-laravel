import React, { useRef } from 'react';
import { Card, Space, Typography, message, Button, Divider, Input, Tag } from 'antd';
import XinForm from '@/components/XinForm';
import type { XinFormRef } from '@/components/XinForm';
import type { XinColumn } from '@/components/XinFormField/FieldRender';

const { Title, Paragraph, Text } = Typography;

// 基础表单字段配置
const basicColumns: XinColumn<any>[] = [
  {
    name: 'username',
    label: '用户名',
    valueType: 'text',
    rules: [{ required: true, message: '请输入用户名' }],
    fieldProps: {
      placeholder: '请输入用户名',
    },
  },
  {
    name: 'password',
    label: '密码',
    valueType: 'password',
    rules: [{ required: true, message: '请输入密码' }],
    fieldProps: {
      placeholder: '请输入密码',
    },
  },
  {
    name: 'email',
    label: '邮箱',
    valueType: 'text',
    rules: [
      { required: true, message: '请输入邮箱' },
      { type: 'email', message: '请输入有效的邮箱地址' },
    ],
    fieldProps: {
      placeholder: '请输入邮箱地址',
    },
  },
  {
    name: 'description',
    label: '个人简介',
    valueType: 'textarea',
    fieldProps: {
      placeholder: '请输入个人简介',
      rows: 3,
    },
  },
];

// Grid 布局表单字段配置
const gridColumns: XinColumn<any>[] = [
  {
    name: 'name',
    label: '姓名',
    valueType: 'text',
    colProps: { span: 12 },
    rules: [{ required: true, message: '请输入姓名' }],
    fieldProps: { placeholder: '请输入姓名' },
  },
  {
    name: 'age',
    label: '年龄',
    valueType: 'digit',
    colProps: { span: 12 },
    fieldProps: { placeholder: '请输入年龄', min: 0, max: 150, style: { width: '100%' } },
  },
  {
    name: 'gender',
    label: '性别',
    valueType: 'radio',
    colProps: { span: 12 },
    fieldProps: {
      options: [
        { label: '男', value: 'male' },
        { label: '女', value: 'female' },
      ],
    },
  },
  {
    name: 'status',
    label: '状态',
    valueType: 'switch',
    colProps: { span: 12 },
    valuePropName: 'checked',
  },
  {
    name: 'birthDate',
    label: '出生日期',
    valueType: 'date',
    colProps: { span: 12 },
    fieldProps: { style: { width: '100%' } },
  },
  {
    name: 'salary',
    label: '薪资',
    valueType: 'money',
    colProps: { span: 12 },
    fieldProps: { style: { width: '100%' }, placeholder: '请输入薪资' },
  },
];

// 完整表单字段配置 - 展示所有字段类型
const fullColumns: XinColumn<any>[] = [
  {
    name: 'text',
    label: '文本输入',
    valueType: 'text',
    tooltip: '这是一个帮助提示',
    fieldProps: { placeholder: '普通文本输入' },
  },
  {
    name: 'password',
    label: '密码输入',
    valueType: 'password',
    fieldProps: { placeholder: '密码输入' },
  },
  {
    name: 'digit',
    label: '数字输入',
    valueType: 'digit',
    extra: '请输入0-100之间的数字',
    fieldProps: {
      placeholder: '数字输入',
      min: 0,
      max: 100,
    },
  },
  {
    name: 'money',
    label: '金额输入',
    valueType: 'money',
    fieldProps: {
      placeholder: '金额输入',
      width: '100%',
    },
  },
  {
    name: 'select',
    label: '下拉选择',
    valueType: 'select',
    fieldProps: {
      placeholder: '请选择',
      options: [
        { label: '选项一', value: 'option1' },
        { label: '选项二', value: 'option2' },
        { label: '选项三', value: 'option3' },
      ],
    },
  },
  {
    name: 'treeSelect',
    label: '树形选择',
    valueType: 'treeSelect',
    fieldProps: {
      placeholder: '请选择',
      treeData: [
        { title: '父节点1', value: 'parent1', children: [
          { title: '子节点1-1', value: 'child1-1' },
          { title: '子节点1-2', value: 'child1-2' },
        ]},
        { title: '父节点2', value: 'parent2' },
      ],
    },
  },
  {
    name: 'cascader',
    label: '级联选择',
    valueType: 'cascader',
    fieldProps: {
      placeholder: '请选择',
      options: [
        { label: '浙江', value: 'zhejiang', children: [
          { label: '杭州', value: 'hangzhou' },
          { label: '宁波', value: 'ningbo' },
        ]},
        { label: '江苏', value: 'jiangsu', children: [
          { label: '南京', value: 'nanjing' },
          { label: '苏州', value: 'suzhou' },
        ]},
      ],
    },
  },
  {
    name: 'radio',
    label: '单选框',
    valueType: 'radio',
    fieldProps: {
      options: [
        { label: '选项A', value: 'a' },
        { label: '选项B', value: 'b' },
        { label: '选项C', value: 'c' },
      ],
    },
  },
  {
    name: 'radioButton',
    label: '单选按钮',
    valueType: 'radioButton',
    fieldProps: {
      options: [
        { label: '按钮A', value: 'a' },
        { label: '按钮B', value: 'b' },
        { label: '按钮C', value: 'c' },
      ],
    },
  },
  {
    name: 'checkbox',
    label: '多选框',
    valueType: 'checkbox',
    fieldProps: {
      options: [
        { label: '选项1', value: '1' },
        { label: '选项2', value: '2' },
        { label: '选项3', value: '3' },
      ],
    },
  },
  {
    name: 'switch',
    label: '开关',
    valueType: 'switch',
    valuePropName: 'checked',
  },
  {
    name: 'rate',
    label: '评分',
    valueType: 'rate',
  },
  {
    name: 'slider',
    label: '滑动条',
    valueType: 'slider',
  },
  {
    name: 'date',
    label: '日期选择',
    valueType: 'date',
  },
  {
    name: 'dateTime',
    label: '日期时间',
    valueType: 'dateTime',
  },
  {
    name: 'dateRange',
    label: '日期范围',
    valueType: 'dateRange',
  },
  {
    name: 'time',
    label: '时间选择',
    valueType: 'time',
  },
  {
    name: 'color',
    label: '颜色选择',
    valueType: 'color',
  },
  {
    name: 'icon',
    label: '图标选择',
    valueType: 'icon',
  },
  {
    name: 'textarea',
    label: '多行文本',
    valueType: 'textarea',
    fieldProps: { placeholder: '请输入多行文本', rows: 3 },
  },
];

// 字段分组配置
const groupColumns: XinColumn<any>[] = [
  {
    valueType: 'divider',
    label: '基本信息',
    colProps: {span: 24}
  },
  {
    name: 'name',
    label: '姓名',
    valueType: 'text',
    colProps: { span: 12 },
    rules: [{ required: true, message: '请输入姓名' }],
  },
  {
    name: 'phone',
    label: '电话',
    valueType: 'text',
    colProps: { span: 12 },
    tooltip: '请输入11位手机号',
  },
  {
    valueType: 'divider',
    label: '部门信息',
    colProps: {span: 24}
  },
  {
    name: 'department',
    label: '部门',
    valueType: 'select',
    colProps: { span: 12 },
    fieldProps: {
      options: [
        { label: '技术部', value: 'tech' },
        { label: '产品部', value: 'product' },
        { label: '运营部', value: 'operation' },
      ],
    },
  },
  {
    name: 'position',
    label: '职位',
    valueType: 'text',
    colProps: { span: 12 },
  },
  {
    name: 'remark',
    label: '备注',
    valueType: 'textarea',
    extra: '可选填写',
  },
];

// 依赖联动配置
interface DependencyFormData {
  hasDiscount: boolean;
  discount: number;
  productType: string;
  subType: string;
}

const dependencyColumns: XinColumn<DependencyFormData>[] = [
  {
    name: 'hasDiscount',
    label: '是否有折扣',
    valueType: 'switch',
    valuePropName: 'checked',
    tooltip: '开启后可设置折扣比例',
  },
  {
    name: 'discount',
    label: '折扣比例',
    valueType: 'slider',
    dependency: {
      dependencies: ['hasDiscount'],
      visible: (values) => values.hasDiscount === true,
    },
    fieldProps: {
      min: 0,
      max: 100,
      marks: { 0: '0%', 50: '50%', 100: '100%' },
    },
  },
  {
    name: 'productType',
    label: '产品类型',
    valueType: 'select',
    fieldProps: {
      placeholder: '请选择产品类型',
      options: [
        { label: '电子产品', value: 'electronics' },
        { label: '服装', value: 'clothing' },
        { label: '食品', value: 'food' },
      ],
    },
  },
  {
    name: 'subType',
    label: '子类型',
    valueType: 'select',
    dependency: {
      dependencies: ['productType'],
      visible: (values) => !!values.productType,
      disabled: (values) => !values.productType,
      fieldProps: (values) => {
        const subTypeOptions: Record<string, { label: string; value: string }[]> = {
          electronics: [
            { label: '手机', value: 'phone' },
            { label: '电脑', value: 'computer' },
            { label: '平板', value: 'tablet' },
          ],
          clothing: [
            { label: '上衣', value: 'top' },
            { label: '裤子', value: 'pants' },
            { label: '鞋子', value: 'shoes' },
          ],
          food: [
            { label: '零食', value: 'snack' },
            { label: '饮料', value: 'drink' },
            { label: '水果', value: 'fruit' },
          ],
        };
        return {
          placeholder: '请先选择产品类型',
          options: subTypeOptions[values.productType as string] || [],
        };
      },
    },
  },
];

// 自定义渲染配置
const customColumns: XinColumn<any>[] = [
  {
    name: 'username',
    label: '用户名',
    valueType: 'text',
    rules: [{ required: true, message: '请输入用户名' }],
  },
  {
    name: 'tags',
    label: '标签',
    valueType: 'text',
    renderField: (_, form) => (
      <Space>
        <Input 
          placeholder="输入标签后按回车" 
          onPressEnter={(e) => {
            const value = (e.target as HTMLInputElement).value;
            const tags = form.getFieldValue('tags') || [];
            if (value && !tags.includes(value)) {
              form.setFieldValue('tags', [...tags, value]);
            }
            (e.target as HTMLInputElement).value = '';
          }}
        />
        <Space>
          {(form.getFieldValue('tags') || []).map((tag: string, index: number) => (
            <Tag 
              key={index} 
              closable 
              onClose={() => {
                const tags = form.getFieldValue('tags') || [];
                form.setFieldValue('tags', tags.filter((_: string, i: number) => i !== index));
              }}
            >
              {tag}
            </Tag>
          ))}
        </Space>
      </Space>
    ),
  },
  {
    name: 'hiddenField',
    label: '隐藏字段',
    valueType: 'text',
    hidden: true,
  },
];

// Modal/Drawer 表单字段配置
const modalColumns: XinColumn<any>[] = [
  {
    name: 'title',
    label: '标题',
    valueType: 'text',
    rules: [{ required: true, message: '请输入标题' }],
    fieldProps: { placeholder: '请输入标题' },
  },
  {
    name: 'type',
    label: '类型',
    valueType: 'select',
    rules: [{ required: true, message: '请选择类型' }],
    fieldProps: {
      placeholder: '请选择类型',
      options: [
        { label: '类型一', value: 'type1' },
        { label: '类型二', value: 'type2' },
      ],
    },
  },
  {
    name: 'priority',
    label: '优先级',
    valueType: 'radioButton',
    fieldProps: {
      options: [
        { label: '低', value: 'low' },
        { label: '中', value: 'medium' },
        { label: '高', value: 'high' },
      ],
    },
  },
  {
    name: 'content',
    label: '内容',
    valueType: 'textarea',
    fieldProps: { placeholder: '请输入内容', rows: 4 },
  },
];

/**
 * XinForm 组件使用示例页面
 */
const XinFormExample: React.FC = () => {
  const modalFormRef = useRef<XinFormRef>(undefined);
  const drawerFormRef = useRef<XinFormRef>(undefined);

  const handleFinish = async (values: any) => {
    console.log('表单提交:', values);
    message.success('表单提交成功！');
    return true;
  };

  return (
    <div>
      <Typography style={{ margin: '12px 0 24px 0' }}>
        <Title level={2}>XinForm 动态表单组件示例</Title>
        <Paragraph>
          基于 Ant Design Form 封装的 JSON 配置动态表单组件，支持多种布局和丰富的表单字段类型。
        </Paragraph>
      </Typography>

      <Space direction="vertical" size="large" style={{ width: '100%' }}>
        {/* 基础表单 */}
        <Card title="基础表单" variant={'borderless'}>
          <Paragraph type="secondary">
            最简单的表单用法，通过 columns 配置表单字段。
          </Paragraph>
          <XinForm
            columns={basicColumns}
            layout="vertical"
            onFinish={handleFinish}
          />
        </Card>

        {/* Grid 布局表单 */}
        <Card title="Grid 布局表单" variant={'borderless'}>
          <Paragraph type="secondary">
            使用 grid 属性开启栅格布局，通过 colProps 控制每列宽度。
          </Paragraph>
          <XinForm
            columns={gridColumns}
            layout="vertical"
            grid={true}
            rowProps={{ gutter: 16 }}
            onFinish={handleFinish}
          />
        </Card>

        {/* 所有字段类型 */}
        <Card title="所有字段类型" variant={'borderless'}>
          <Paragraph type="secondary">
            展示 XinForm 支持的所有字段类型：text、password、digit、money、select、treeSelect、cascader、radio、checkbox、switch、rate、slider、date、time、color、icon、textarea 等。
            <br />
            新增功能：<Text code>tooltip</Text> 提示信息、<Text code>extra</Text> 额外说明、<Text code>width</Text> 组件宽度。
          </Paragraph>
          <XinForm
            columns={fullColumns}
            layout="vertical"
            onFinish={handleFinish}
          />
        </Card>

        {/* 字段分组 */}
        <Card title="字段分组" variant={'borderless'}>
          <Paragraph type="secondary">
            使用 <Text code>group</Text> 属性对表单字段进行分组，自动显示分割线和标题。
          </Paragraph>
          <XinForm
            columns={groupColumns}
            layout="vertical"
            grid={true}
            rowProps={{ gutter: 16 }}
            onFinish={handleFinish}
          />
        </Card>

        {/* 依赖联动 */}
        <Card title="依赖联动" variant={'borderless'}>
          <Paragraph type="secondary">
            使用 <Text code>dependency</Text> 属性实现字段间的依赖联动：
            <br />
            - <Text code>visible</Text>：根据其他字段值控制显示/隐藏
            <br />
            - <Text code>disabled</Text>：根据其他字段值控制禁用状态
            <br />
            - <Text code>fieldProps</Text>：根据其他字段值动态设置组件属性
          </Paragraph>
          <XinForm<DependencyFormData>
            columns={dependencyColumns}
            layout="vertical"
            initialValues={{ hasDiscount: false }}
            onFinish={handleFinish}
          />
        </Card>

        {/* 自定义渲染 */}
        <Card title="自定义渲染" variant={'borderless'}>
          <Paragraph type="secondary">
            使用 <Text code>valueType="custom"</Text> + <Text code>renderField</Text> 实现完全自定义的表单字段渲染。
            <br />
            使用 <Text code>hidden</Text> 属性隐藏字段（支持布尔值或函数）。
          </Paragraph>
          <XinForm
            columns={customColumns}
            layout="vertical"
            onFinish={handleFinish}
          />
        </Card>

        {/* ModalForm 弹窗表单 */}
        <Card title="ModalForm 弹窗表单" variant={'borderless'}>
          <Paragraph type="secondary">
            使用 layoutType="ModalForm" 实现弹窗表单，支持 trigger 触发器或 formRef 控制打开/关闭。
          </Paragraph>
          <Space>
            <XinForm
              columns={modalColumns}
              layoutType="ModalForm"
              layout="vertical"
              trigger={<Button type="primary">使用 Trigger 打开</Button>}
              modalProps={{ title: '新建任务', width: 520 }}
              onFinish={handleFinish}
            />
            <Button onClick={() => modalFormRef.current?.open()}>
              使用 Ref 打开
            </Button>
            <XinForm
              columns={modalColumns}
              layoutType="ModalForm"
              layout="vertical"
              formRef={modalFormRef}
              modalProps={{ title: '新建任务 (Ref)', width: 520 }}
              onFinish={handleFinish}
            />
          </Space>
        </Card>

        {/* DrawerForm 抽屉表单 */}
        <Card title="DrawerForm 抽屉表单" variant={'borderless'}>
          <Paragraph type="secondary">
            使用 layoutType="DrawerForm" 实现抽屉表单，适用于复杂表单或需要更大空间的场景。
          </Paragraph>
          <Space>
            <XinForm
              columns={modalColumns}
              layoutType="DrawerForm"
              layout="vertical"
              trigger={<Button type="primary">使用 Trigger 打开</Button>}
              drawerProps={{ title: '新建任务', width: 520 }}
              onFinish={handleFinish}
            />
            <Button onClick={() => drawerFormRef.current?.open()}>
              使用 Ref 打开
            </Button>
            <XinForm
              columns={modalColumns}
              layoutType="DrawerForm"
              layout="vertical"
              formRef={drawerFormRef}
              drawerProps={{ title: '新建任务 (Ref)', width: 520 }}
              onFinish={handleFinish}
            />
          </Space>
        </Card>

        {/* 自定义提交按钮 */}
        <Card title="自定义提交按钮" variant={'borderless'}>
          <Paragraph type="secondary">
            通过 submitter 配置项自定义提交按钮文本、样式，或完全自定义渲染。
          </Paragraph>
          <Divider orientation="left">自定义按钮文本</Divider>
          <XinForm
            columns={basicColumns.slice(0, 2)}
            layout="vertical"
            onFinish={handleFinish}
            submitter={{
              submitText: '确认提交',
              resetText: '清空表单',
            }}
          />
          <Divider orientation="left">隐藏提交按钮</Divider>
          <Text type="secondary">设置 submitter.render = false 隐藏提交按钮</Text>
          <XinForm
            columns={basicColumns.slice(0, 2)}
            layout="vertical"
            onFinish={handleFinish}
            submitter={{ render: false }}
          />
        </Card>
      </Space>
    </div>
  );
};

export default XinFormExample;
