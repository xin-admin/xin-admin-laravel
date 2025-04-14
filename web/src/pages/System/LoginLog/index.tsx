import XinTable from '@/components/Xin/XinTable';
import { IAdminLoginLog } from '@/domain/iAdminLoginLog';

export default () => {

  return (
    <XinTable<IAdminLoginLog>
      api={'/admin/loginlog'}
      accessName={'admin.loginlog'}
      addShow={false}
      operateShow={false}
      rowKey={'log_id'}
      columns={[
        { title: '日志ID', dataIndex: 'log_id', hideInForm: true, sorter: true, align: 'center', hideInSearch: true, },
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
