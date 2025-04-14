import { useEffect, useState } from 'react';
import { listApi } from '@/services/common/table';
import { Table, TableProps } from 'antd';
import { ProCard } from '@ant-design/pro-components';

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

  const [data, setData] = useState<IDataType[]>([]);

  useEffect(() => {
    listApi('/system/watcher/request').then((res) => {
      setData(res.data);
    })
  }, []);

  const columns: TableProps<IDataType>['columns'] = [
    {
      title: 'Verb',
      dataIndex: ['content','method'],
      key: 'content.method',
    },
    {
      title: 'Path',
      dataIndex: ['content','uri'],
      key: 'content.uri',
    },
    {
      title: 'IP-Address',
      dataIndex: ['content','ip_address'],
      key: 'content.ip_address',
    },
    {
      title: 'Hostname',
      dataIndex: ['content','hostname'],
      key: 'content.hostname',
    },
    {
      title: 'Status',
      dataIndex: ['content','response_status'],
      key: 'content.response_status',
    },
    {
      title: 'Duration',
      dataIndex: ['content','duration'],
      key: 'content.duration',
      render: (text) => <>{text} ms</>
    },
    {
      title: 'Happened',
      dataIndex: 'created_at',
      key: 'created_at',
    },
    {
      render: () => (
        <div style={{ width: 20, height: 20 }}>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM6.75 9.25a.75.75 0 000 1.5h4.59l-2.1 1.95a.75.75 0 001.02 1.1l3.5-3.25a.75.75 0 000-1.1l-3.5-3.25a.75.75 0 10-1.02 1.1l2.1 1.95H6.75z"
                  clip-rule="evenodd" />
          </svg>
        </div>
      )
    }
  ];

  return <ProCard>
    <Table<IDataType>
      columns={columns}
      dataSource={data}
      pagination={{ pageSize: 50 }}
      size={'small'}
      bordered
    />
  </ProCard>;
}
