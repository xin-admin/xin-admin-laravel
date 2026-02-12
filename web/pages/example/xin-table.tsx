import { Button, Tag } from 'antd';
import { ExportOutlined } from '@ant-design/icons';
import type { XinTableColumn } from '@/components/XinTableV2/typings';
import XinTableV2 from '@/components/XinTableV2';

// 模拟用户数据类型
interface User {
  id: number;
  name: string;
  email: string;
  age: number;
  status: number;
  role: string;
  department: string;
  createdAt: string;
}

// 模拟数据
const mockData: User[] = Array.from({ length: 50 }, (_, i) => ({
  id: i + 1,
  name: `用户${i + 1}`,
  email: `user${i + 1}@example.com`,
  age: Math.floor(Math.random() * 40) + 20,
  status: Math.random() > 0.3 ? 1 : 0,
  role: ['管理员', '编辑', '访客'][Math.floor(Math.random() * 3)],
  department: ['技术部', '产品部', '运营部', '市场部'][Math.floor(Math.random() * 4)],
  createdAt: new Date(Date.now() - Math.random() * 10000000000).toLocaleDateString(),
}));

/**
 * XinTableV2 示例页面
 */
const XinTableExample = () => {

  // 表格列配置
  const columns: XinTableColumn<User>[] = [
    {
      title: '序号',
      width: 60,
      valueType: 'text',
      dataIndex: 'id',
    },
    {
      dataIndex: 'name',
      title: '用户名',
      width: 120,
      valueType: 'text',
      required: true,
    },
    {
      dataIndex: 'email',
      title: '邮箱',
      valueType: 'text',
      width: 200,
      ellipsis: true,
    },
    {
      dataIndex: 'age',
      title: '年龄',
      width: 80,
      valueType: 'digit',
      sorter: (a: User, b: User) => a.age - b.age,
      hideInSearch: true
    },
    {
      dataIndex: 'status',
      title: '状态',
      width: 100,
      valueType: 'select',
      // 自定义渲染 - 用户外理显示内容
      render: (value: number) => {
        const enumMap: Record<number, { text: string; color: string }> = {
          1: { text: '启用', color: 'green' },
          0: { text: '禁用', color: 'red' },
        };
        const item = enumMap[value];
        return item ? <Tag color={item.color}>{item.text}</Tag> : '-';
      },
      filters: [
        { text: '启用', value: 1 },
        { text: '禁用', value: 0 },
      ],
    },
    {
      dataIndex: 'role',
      title: '角色',
      width: 100,
      valueType: 'select',
      fieldProps: {
        options: [
          { label: '管理员', value: '管理员' },
          { label: '编辑', value: '编辑' },
          { label: '访客', value: '访客' },
        ],
      }
    },
    {
      dataIndex: 'department',
      title: '部门',
      width: 120,
      valueType: 'select',
      fieldProps: {
        options: [
          { label: '技术部', value: '技术部' },
          { label: '产品部', value: '产品部' },
          { label: '运营部', value: '运营部' },
          { label: '市场部', value: '市场部' },
        ],
      },
      hideInSearch: true,
    },
    {
      dataIndex: 'createdAt',
      title: '创建时间',
      width: 120,
      hideInForm: true,
      hideInSearch: true,
    },
  ];

  // 自定义工具栏
  const customToolbar = [
    <Button key="export" icon={<ExportOutlined />}>
      导出
    </Button>,
  ];

  return (
    <XinTableV2
      columns={columns}
      rowKey="id"
      dataSource={mockData}
      accessName='system.user.list'
      api={'/system-user/list'}
      toolBarRender={customToolbar}
    />
  );
};

export default XinTableExample;

