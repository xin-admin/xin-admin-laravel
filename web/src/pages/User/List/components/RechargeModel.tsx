import { BetaSchemaForm } from '@ant-design/pro-components';
import { recharge } from '@/services/user';
import { message } from 'antd';

const RechargeModel = (props: { data: any }) => {
  const {data} = props;

  return (
    <BetaSchemaForm
      layoutType={'ModalForm'}
      trigger={<a>用户充值</a>}
      title={'用户充值'}
      onFinish={async (data: any) => {
        await recharge(data)
        message.success('充值成功')
        return true
      }}
      columns={[
        {
          title: '用户ID',
          dataIndex: 'user_id',
          initialValue: data.id,
          readonly: true,
          valueType: 'number'
        },
        {
          title: '剩余金额',
          dataIndex: 'money',
          initialValue: data.money,
          valueType: 'money',
          readonly: true,
        },
        {
          title: '充值方式',
          dataIndex: 'mode',
          valueType: "radio",
          initialValue: 'inc',
          valueEnum: {
            'inc': { text: '增加' },
            'dec': { text: '减少' },
            'end': { text: '最终金额' },
          }
        },
        {
          title: '变更金额',
          dataIndex: 'amount',
          valueType: 'money'
        },
        {
          title: '管理员备注',
          dataIndex: 'remark',
          valueType: 'textarea'
        }
      ]}

    />
  )
}

export default RechargeModel;
