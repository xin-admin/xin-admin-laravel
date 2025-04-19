import { ReactNode, useState } from 'react';
import dayjs from 'dayjs';
import XinTable from '@/components/Xin/XinTable';
import { DatePicker, Tag, Popover, Button } from 'antd';
import { XinTableColumn } from '@/components/Xin/XinTable/typings';
import { ProTableProps } from '@ant-design/pro-components';
import { EyeOutlined } from '@ant-design/icons';

export default function() {

  const [params, setParams] = useState({
    type: 'cache',
    date: dayjs().format('YYYY-MM-DD'),
  })

  const cacheActionTypeRender = (type : ReactNode, record: any) => {
    if (type === 'hit') return <Tag color="success">{record.type}</Tag>;
    if (type === 'set') return <Tag color="processing">{record.type}</Tag>;
    if (type === 'forget') return <Tag color="warning">{record.type}</Tag>;
    if (type === 'missed') return <Tag color="error">{record.type}</Tag>;
    return <Tag>{record.type}</Tag>;
  }

  const columns: XinTableColumn<any>[] = [
    {
      title: '类型',
      dataIndex: 'type',
      align: 'center',
      render: cacheActionTypeRender
    },
    { title: 'key', dataIndex: 'key', align: 'center' },
    { title: '过期时间', dataIndex: 'expiration', align: 'center', renderText: (expiration) => `${expiration ?? '-'} 秒` },
    {
      title: 'Value',
      dataIndex: 'value',
      valueType: 'jsonCode',
      renderText: (value) => JSON.stringify(value),
      render: (node, record) => (<>
        {record.value ? (
          <Popover content={(
            <div style={{ maxHeight: 400, overflow: 'auto' }}>
              {node}
            </div>
          )} trigger="click" >

            <Button type={'link'}><EyeOutlined /> 查看</Button>
          </Popover>
        ): '-'}
      </>),
      align: 'center',
    },
    { title: '主机', dataIndex: 'host_name', align: 'center',},
    { title: '记录时间', dataIndex: 'recorded_at', align: 'center',},
  ]

  const tableProps: ProTableProps<any, any> = {
    headerTitle: '缓存日志',
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
      api={'/system/watcher/cache'}
      accessName={''}
      addShow={false}
      operateShow={false}
      rowKey={''}
      tableProps={tableProps}
      columns={columns}
    />
  )
}
