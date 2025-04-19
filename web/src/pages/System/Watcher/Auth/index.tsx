import XinTable from '@/components/Xin/XinTable';
import { DatePicker, Tag } from 'antd';
import { useState } from 'react';
import dayjs from 'dayjs';
import { XinTableColumn } from '@/components/Xin/XinTable/typings';
import { ProTableProps } from '@ant-design/pro-components';

export default () => {
  const [params, setParams] = useState({
    type: 'auth',
    date: dayjs().format('YYYY-MM-DD'),
  })

  const columns: XinTableColumn<any>[] = [
    {
      title: '驱动',
      dataIndex: 'user_type',
      width: 80,
      align: 'center',
      render: (_, record) => <Tag color={'processing'}>{record.user_type}</Tag>
    },
    {
      title: '用户ID',
      dataIndex: ['user', 'id'],
      width: 80,
      align: 'center',
      render: (_, record) => <Tag color={'processing'}>{record.user.id}</Tag>
    },
    { title: '用户名', dataIndex: ['user', 'name'], width: 80, ellipsis: true, align: 'center', },
    { title: '邮箱', dataIndex: ['user', 'email'], width: 240, ellipsis: true, },
    { title: '头像', dataIndex: ['user', 'avatar'], valueType: 'avatar', width: 80, align: 'center', },
    { title: 'IP地址', dataIndex: 'ipaddr', valueType: 'text', },
    { title: '浏览器', dataIndex: 'browser', valueType: 'text', hideInSearch: true, },
    { title: '操作系统', dataIndex: 'os', valueType: 'text', hideInSearch: true, },
    { title: '登录地址', dataIndex: 'login_location', valueType: 'text',  hideInSearch: true, },
    { title: '状态', dataIndex: 'type', valueType: 'text',
      valueEnum: {
        'logout': { text: '登出', status: 'warning' },
        'login': { text: '登录', status: 'success' },
        'loginFailed': { text: '登录失败', status: 'error' },
      }
    },
    { title: '登录时间', dataIndex: 'login_time', hideInForm: true, valueType: 'fromNow', hideInSearch: true, },
  ]

  const tableProps: ProTableProps<any, any> = {
    headerTitle: '登录日志',
    toolbar: { settings: [] },
    toolBarRender: () => [
      <DatePicker
        value={dayjs(params.date)}
        onChange={(date) => setParams({ type: 'request', date: date.format('YYYY-MM-DD') })}
      />
    ],
    search: false,
    params: params,
    pagination: {
      showSizeChanger: true
    },
  }

  return (
    <XinTable
      api={'/system/watcher/auth'}
      accessName={''}
      addShow={false}
      operateShow={false}
      rowKey={''}
      tableProps={tableProps}
      columns={columns}
    />
  )
}
