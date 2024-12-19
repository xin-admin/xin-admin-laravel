import XinTable from '@/components/XinTable'
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import React from 'react';
import { UserOutlined } from '@ant-design/icons';
import { Avatar, Space, Popover } from 'antd';
import XinDict from '@/components/XinDict';
import { useModel } from '@umijs/max';

/**
 *  Api 接口
 */
const api = '/user/money/record'

/**
 *  数据类型
 */
interface Data {
  id: number;
  user_id: string;
  user: USER.UserInfo;
  scene: string;
  money: string;
  describe: string;
  remark: string;
  created_at: string;
}

/**
 * 表格渲染
 */
const User: React.FC = () => {
  const { dictEnum } = useModel('dictModel')

  const userInfo = (info: USER.UserInfo) => {
    return (
      <>
        <p>用户ID：{info.id}</p>
        <p>用户昵称：{info.nickname}</p>
        <p>用户名：{info.username}</p>
        <p>用户邮箱：{info.email}</p>
        <p>用户余额：{info.money} ￥</p>
      </>
    )
  }

  const columns: ProFormColumnsAndProColumns<Data>[] =
    [
      {
        valueType: 'text',
        title: '用户',
        order: 98,
        dataIndex: 'user_id',
        render: (_, date) => (
          <Popover placement="left" content={userInfo(date.user)} title={date.user.nickname}>
            <Space style={{ display: 'flex' }}>
              <Avatar src={date.user.avatar_url} icon={<UserOutlined />}></Avatar>
              {date.user.nickname}
            </Space>
          </Popover>
        )
      },
      {
        valueType: 'text',
        title: '类型',
        order: 93,
        dataIndex: 'scene',
        hideInSearch: true,
        valueEnum: dictEnum.get('moneyLog'),
        render: (_, date) => <XinDict value={date.scene} dict={'moneyLog'} />,
        filters: true
      },
      {
        valueType: 'money',
        title: '变动金额',
        order: 91,
        dataIndex: 'money',
        search: false
      },
      {
        valueType: 'text',
        title: '描述/备注',
        order: 90,
        search: false,
        dataIndex: 'describe',
      },
      {
        valueType: 'dateTime',
        title: '变动时间',
        order: 1,
        hideInSearch: true,
        dataIndex: 'created_at',
        sorter: true,
      },
      {
        valueType: 'date',
        title: '变动时间',
        order: 1,
        hideInTable: true,
        dataIndex: 'created_at',
      }
    ]
  return (
    <XinTable<Data>
      tableApi={api}
      columns={columns}
      headerTitle={'用户余额变动记录'}
      operateShow={false}
      rowSelectionShow={false}
      addShow={false}
      accessName={'user.moneyLog'}
    />
  )

}

export default User
