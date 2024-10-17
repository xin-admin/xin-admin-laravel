import React, { useEffect, useState } from 'react';
import { getMoneyLog } from '@/services/api/user';
import { Card, Table } from 'antd';
import { TableProps } from 'antd';
import XinDict from '@/components/XinDict';
import UserLayout from '@/pages/frontend/User/components/UserLayout';

interface DataType {
  id: number;
  create_time: string;
  describe: string;
  money: string;
  scene: string;
}

const MoneyLog = () => {
  const [params,setParams] = useState({
    page: 1,
    pageSize: 10
  });

  const [data,setData] = useState<DataType[]>([]);

  useEffect(() => {
    getMoneyLog(params).then((res) => {
      setData(res.data.data)
    })
  },[params])


  const columns: TableProps<DataType>['columns'] = [
    {
      title: '说明',
      dataIndex: 'describe',
    },
    {
      title: '类型',
      dataIndex: 'scene',
      render: (_, date) => <XinDict value={date.scene} dict={'moneyLog'} />
    },
    {
      title: '变动金额',
      dataIndex: 'money',
    },
  ]

  return (
    <UserLayout>
      <Card title={'余额记录'}>
        <Table
          columns={columns}
          dataSource={data}
          bordered
          rowKey={'id'}
          pagination={{
            onChange: (page, pageSize) =>{
              setParams({page: page, pageSize: pageSize })
            }
          }}
        />
      </Card>
    </UserLayout>
  )
}

export default MoneyLog;
