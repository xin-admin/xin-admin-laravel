import XinTableV2 from '@/components/XinTableV2';
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import React from 'react';
import XinDict from '@/components/XinDict';
import { useModel } from '@umijs/max';
import { IBalanceRecord } from '@/domain/IBalanceRecord';

/** 余额变动记录表 */
export default () => {
  const { dictEnum } = useModel('dictModel');

  const columns: ProFormColumnsAndProColumns<IBalanceRecord>[] = [
    { valueType: 'digit', title: '用户ID', dataIndex: 'user_id', hideInTable: true },
    { valueType: 'digit', title: '用户ID', dataIndex: ['user', 'user_id'], search: false },
    { valueType: 'text', title: '用户名', dataIndex: ['user', 'username'], search: false },
    { valueType: 'money', title: '昵称', dataIndex: ['user', 'nickname'], search: false },
    { valueType: 'avatar', title: '头像', dataIndex: ['user', 'avatar_url'], search: false },
    {
      valueType: 'text', title: '类型', dataIndex: 'scene', hideInSearch: true, filters: true,
      valueEnum: dictEnum.get('moneyLog'),
      render: (_, date) => <XinDict value={date.scene} dict={'moneyLog'} />,
    },
    { valueType: 'money', title: '变动金额', dataIndex: 'balance', search: false },
    { valueType: 'money', title: '变动前', dataIndex: 'before', search: false },
    { valueType: 'money', title: '变动后', dataIndex: 'after', search: false },
    { valueType: 'text', title: '描述/备注', dataIndex: 'describe', search: false },
    { valueType: 'dateTime', title: '变动时间', hideInSearch: true, dataIndex: 'created_at', sorter: true },
    { valueType: 'date', title: '变动时间', hideInTable: true, dataIndex: 'created_at' },
  ];

  return (
    <XinTableV2<IBalanceRecord>
      api={'/user/balance/record'}
      columns={columns}
      operateShow={false}
      addShow={false}
      rowKey={'id'}
      tableProps={{ headerTitle: '余额变动记录' }}
      accessName={'user.balance'}
    />
  );
};
