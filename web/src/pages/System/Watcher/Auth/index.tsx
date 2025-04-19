import XinTable from '@/components/Xin/XinTable';
import { DatePicker } from 'antd';
import { useState } from 'react';
import dayjs from 'dayjs';

export default () => {
  const [params, setParams] = useState({
    type: 'auth',
    date: dayjs().format('YYYY-MM-DD'),
  })
  return (
    <XinTable
      api={'/system/watcher/auth'}
      accessName={''}
      addShow={false}
      operateShow={false}
      rowKey={''}
      tableProps={{
        headerTitle: '请求记录',
        toolbar: { settings: [] },
        toolBarRender: () => [
          <DatePicker
            onChange={(date, dateString) => setParams({ type: 'request', date: date.format('YYYY-MM-DD') })}
          />
        ],
        search: false,
        params: params,
        pagination: {
          showSizeChanger: true
        },
      }}
      columns={[
        { title: '用户名', dataIndex: 'username', valueType: 'text' },
        { title: 'IP地址', dataIndex: 'ipaddr', valueType: 'text', },
        { title: '浏览器', dataIndex: 'browser', valueType: 'text', hideInSearch: true, },
        { title: '操作系统', dataIndex: 'os', valueType: 'text', hideInSearch: true, },
        { title: '登录地址', dataIndex: 'login_location', valueType: 'text',  hideInSearch: true, },
        { title: '状态', dataIndex: 'status', valueType: 'text',
          valueEnum: {
            '1': { text: '失败', status: 'error' },
            '0': { text: '成功', status: 'success' },
          }
        },
        { title: '登录消息', dataIndex: 'msg', valueType: 'text', hideInSearch: true, ellipsis: true, },
        { title: '登录时间', dataIndex: 'login_time', hideInForm: true, valueType: 'fromNow', hideInSearch: true, },
      ]}
    />
  )
}
