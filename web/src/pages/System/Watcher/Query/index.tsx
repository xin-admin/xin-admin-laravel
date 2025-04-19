import { useState } from 'react';
import dayjs from 'dayjs';
import XinTable from '@/components/Xin/XinTable';
import { DatePicker, Tag } from 'antd';
import { XinTableColumn } from '@/components/Xin/XinTable/typings';
import { ProTableProps } from '@ant-design/pro-components';

export default function() {

  const [params, setParams] = useState({
    type: 'query',
    date: dayjs().format('YYYY-MM-DD'),
  })

  const columns: XinTableColumn<any>[] = [
    {
      title: '驱动',
      dataIndex: 'connection',
      width: 80,
      align: 'center',
      render: (_, record) => <Tag color={'processing'}>{record.connection}</Tag>
    },
    { title: 'sql', dataIndex: 'sql', width: 400, ellipsis: true, },
    { title: '执行文件', dataIndex: 'file', width: 400, ellipsis: true, },
    { title: '执行位置', dataIndex: 'line', width: 80, align: 'center', renderText: (text) => `${text} 行` },
    { title: '用时', dataIndex: 'time', width: 80, align: 'center', renderText: (text) => `${text} ms` },
    { title: '速度', dataIndex: 'slow', width: 80, align: 'center', valueEnum: { true: '慢速', false: '正常' }},
    { title: '主机', dataIndex: 'host_name'},
    { title: '记录时间', dataIndex: 'recorded_at'},
  ]

  const tableProps: ProTableProps<any, any> = {
    headerTitle: 'SQL 日志',
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
    scroll: { x: 1460 }
  }

  return (
    <XinTable
      api={'/system/watcher/query'}
      accessName={''}
      addShow={false}
      operateShow={false}
      rowKey={''}
      tableProps={tableProps}
      columns={columns}
    />
  )
}
