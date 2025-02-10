import { BetaSchemaForm } from '@ant-design/pro-components';
import { editApi } from "@/services/common/table";
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import { IUserList } from '@/domain/iUserList';
import ButtonAccess from '@/components/ButtonAccess';
import { message } from 'antd';

interface UpDatePasswordForm {
  id?: number;
  avatar?: string;
  nickname?: string;
  password?: string;
  rePassword: string;
}

interface ResetPasswordProps {
  user: IUserList
}

export default (props: ResetPasswordProps) => {
  const { user } = props;
  const columns: ProFormColumnsAndProColumns<UpDatePasswordForm>[] = [
    { title: '用户ID', dataIndex: 'id', valueType: 'text', initialValue: user.user_id, readonly: true },
    { title: '密码', dataIndex: 'password', valueType: 'password',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] }
    },
    { title: '确认密码', dataIndex: 'rePassword', valueType: 'password',
      formItemProps: {
        rules: [
          { required: true, message: '该项为必填' },
          ({ getFieldValue }) => ({
            validator(_, value) {
              if (!value || getFieldValue('password') === value) {
                return Promise.resolve();
              }
              return Promise.reject(new Error('两次输入的密码不同'));
            },
          }),
        ]
      }
    },
  ];

  // 重置密码
  const defaultUpdate = async (fields: UpDatePasswordForm) => {
    let data = Object.assign({ user_id: user.user_id }, fields)
    await editApi('/user/list/resetPassword', data)
    message.success('更新密码成功！');
    return true
  }

  return (
    <ButtonAccess auth={'user.list.resetPassword'}>
      <BetaSchemaForm<UpDatePasswordForm>
        trigger={<a>重置密码</a>}
        title={'重置用户密码'}
        layoutType={'ModalForm'}
        rowProps={{
          gutter: [16, 16],
        }}
        colProps={{
          span: 24,
        }}
        grid={true}
        onFinish={defaultUpdate}
        columns={columns}
      />
    </ButtonAccess>
  )
}
