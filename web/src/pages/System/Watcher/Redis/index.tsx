import { useState } from 'react';
import dayjs from 'dayjs';
import XinTable from '@/components/Xin/XinTable';
import { DatePicker, Popover, Tag, Typography } from 'antd';
import { XinTableColumn } from '@/components/Xin/XinTable/typings';
import { ProTableProps } from '@ant-design/pro-components';
const { Text } = Typography;
export default function() {

  const [params, setParams] = useState({
    type: 'redis',
    date: dayjs().format('YYYY-MM-DD'),
  })

  const columns: XinTableColumn<any>[] = [
    {
      title: '链接',
      dataIndex: 'connection',
      align: 'center',
      render: (_, record) =>  <Tag color={'processing'}>{record.connection}</Tag>
    },
    { title: '命令',  dataIndex: 'command', width: 600, render: (dom) => (
        <Popover content={(
          <div style={{ maxHeight: 400, maxWidth: 600, overflow: 'auto' }}>
            {dom}
          </div>
        )} trigger="click" >
          <Text ellipsis style={{ maxWidth: 600 }}>{dom}</Text>
        </Popover>

    )},
    { title: '用时', dataIndex: 'time', align: 'center', renderText: (time) => `${time ?? '-'} ms` },
    { title: '主机', dataIndex: 'host_name', align: 'center',},
    { title: '记录时间', dataIndex: 'recorded_at', align: 'center',},
  ]

  const tableProps: ProTableProps<any, any> = {
    headerTitle: 'Redis 日志',
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
    scroll: { x: 1000 }
  }

  return (
    <XinTable
      api={'/system/watcher/redis'}
      accessName={''}
      addShow={false}
      operateShow={false}
      rowKey={''}
      tableProps={tableProps}
      columns={columns}
    />
  )
}
