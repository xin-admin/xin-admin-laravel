// CRUD 一键生成
import XinTableV2 from '@/components/XinTableV2'
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import { UserOutlined } from '@ant-design/icons';
import { Avatar } from 'antd';
import XinDict from '@/components/XinDict';
import { useModel } from '@@/exports';
import RechargeModel from './components/RechargeModel';
import UpdatePassword from './components/UpdatePassword';
import ButtonAccess from '@/components/ButtonAccess';

/**
 *  Api 接口
 */
const api = '/user/list'

/**
 *  数据类型
 */
interface Data {
  user_id: number;
  mobile: string;
  username: string;
  email: string;
  nickname: string;
  avatar: string;
  gender: string;
  birthday: string;
  balance: string;
  score: string;
  motto: string;
  create_time: string;
  update_time: string;
  avatar_url: string;
}

/**
 * 表格渲染
 */
const User: React.FC = () => {
  const { dictEnum } = useModel('dictModel')
  const columns: ProFormColumnsAndProColumns<Data>[] =
    [
      {
        valueType: 'text',
        title: '搜索用户',
        dataIndex: 'keywordSearch',
        hideInTable: true,
        hideInForm: true,
        order: 99,
        fieldProps: {
          placeholder: '输入ID\\账号\\昵称\\手机号\\邮箱搜索'
        }
      },
      {
        valueType: 'digit',
        title: 'ID',
        hideInForm: true,
        dataIndex: 'user_id',
        sorter: true,
        hideInSearch: true
      },
      {
        valueType: 'text',
        title: '手机号',
        dataIndex: 'mobile',
        hideInSearch: true
      },
      {
        valueType: 'text',
        title: '用户名',
        dataIndex: 'username',
        hideInSearch: true
      },
      {
        valueType: 'text',
        title: '用户邮箱',
        dataIndex: 'email',
        hideInSearch: true
      },
      {
        valueType: 'text',
        title: '昵称',
        dataIndex: 'nickname',
        hideInSearch: true
      },
      {
        valueType: 'text',
        title: '头像',
        dataIndex: 'avatar_url',
        hideInSearch: true,
        render: (_, data) => <Avatar src={data.avatar_url} style={{ backgroundColor: '#87d068' }} icon={<UserOutlined />} />
      },
      {
        valueType: 'radio',
        title: '性别',
        valueEnum: dictEnum.get('sex'),
        render: (_, date) => <XinDict value={date.gender} dict={'sex'} />,
        dataIndex: 'gender',
        hideInSearch: true,
        filters: true
      },
      {
        valueType: 'money',
        title: '余额',
        hideInSearch: true,
        dataIndex: 'balance',
      },
      {
        valueType: 'textarea',
        title: '签名',
        hideInSearch: true,
        hideInTable: true,
        dataIndex: 'motto',
      },
      {
        dataIndex: 'status',
        valueType: 'radio',
        title: '状态',
        hideInSearch: true,
        filters: true,
        valueEnum: {
          0: {
            text: '禁用',
            status: 'Error',
          },
          1: {
            text: '启用',
            status: 'Success',
          },
        },
      },
      {
        valueType: 'date',
        title: '注册时间',
        hideInForm: true,
        dataIndex: 'created_at',
      },
    ]

  return (
    <>
      <XinTableV2<Data>
        api={api}
        columns={columns}
        accessName={'user.list'}
        rowKey={'user_id'}
        deleteShow={false}
        afterOperateRender={(data) => [
          <ButtonAccess auth={'user.list.recharge'} key={'recharge'}>
            <RechargeModel data={data} />
          </ButtonAccess>,
          <ButtonAccess auth={'user.list.resetPassword'} key={'resetPassword'}>
            <UpdatePassword key={'resetPassword'} record={data} />
          </ButtonAccess>
        ]}
      />
    </>
  )

}

export default User
