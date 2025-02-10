import { recharge } from '@/services/user';
import { message } from 'antd';
import { BetaSchemaForm } from '@ant-design/pro-components';
import React from 'react';
import { IUserList } from '@/domain/iUserList';
import ButtonAccess from '@/components/ButtonAccess';

interface RechargeProps {
  user: IUserList;
  afterFn?: () => void;
}

export default (props: RechargeProps) => {
  const { user, afterFn } = props
  return (
    <ButtonAccess auth={'user.list.recharge'}>
      <BetaSchemaForm
        layoutType={'ModalForm'}
        trigger={<a>用户充值</a>}
        title={'用户充值'}
        onFinish={async (data) => {
          await recharge(data);
          message.success('充值成功');
          afterFn?.()
          return true;
        }}
        grid={true}
        colProps={{ span: 12 }}
        columns={[
          {
            title: '用户',
            dataIndex: 'user_id',
            initialValue: user.user_id,
            readonly: true,
            valueType: 'number',
          },
          {
            title: '剩余金额',
            dataIndex: 'balance',
            initialValue: user.balance,
            valueType: 'money',
            readonly: true,
          },
          {
            title: '充值方式', dataIndex: 'mode', valueType: 'radio', initialValue: 'inc',
            valueEnum: {
              'inc': { text: '增加' },
              'dec': { text: '减少' },
              'end': { text: '最终金额' },
            },
          },
          { title: '变更金额', dataIndex: 'amount', valueType: 'money' },
          { title: '管理员备注', dataIndex: 'remark', valueType: 'textarea' },
        ]}
      />
    </ButtonAccess>
  )
}
