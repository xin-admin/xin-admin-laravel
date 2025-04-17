import XinTable from '@/components/Xin/XinTable';
import { XinTableColumn } from '@/components/Xin/XinTable/typings';

interface IDataType {
  uuid?: string;
  sequence?: string;
  batch_id?: string;
  type?: string;
  family_hash?: string,
  content?: any,
  created_at?: string,
}

export default () => {

  const columns: XinTableColumn<IDataType>[] = [
    {
      title: '请求方法',
      align: 'center',
      dataIndex: 'method',
      key: 'method',
    },
    {
      title: 'Path',
      dataIndex: 'uri',
      key: 'uri',
    },
    {
      title: 'IP地址',
      align: 'center',
      dataIndex: 'ip_address',
      key: 'ip_address',
    },
    {
      title: '主机名称',
      align: 'center',
      dataIndex: 'host_name',
      key: 'host_name',
    },
    {
      title: '请求状态',
      align: 'center',
      dataIndex: 'response_status',
      key: 'response_status',
    },
    {
      title: '请求时间',
      align: 'center',
      dataIndex: 'duration',
      key: 'duration',
      render: (text) => <>{text} ms</>
    },
    {
      title: '记录时间',
      align: 'center',
      dataIndex: 'recorded_at',
      key: 'recorded_at',
    }
  ];

  return (
    <XinTable<IDataType>
      api={'/system/watcher/request'}
      rowKey={''}
      accessName={''}
      columns={columns}
      addShow={false}
      operateShow={false}
      tableProps={{
        search: false,
        params: {
          type: 'request'
        },
        pagination: {
          pageSize: 10,
        },
      }}
    />
  );
}
