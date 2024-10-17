import {
  BetaSchemaForm
} from '@ant-design/pro-components';
import { message } from 'antd';
import { editApi } from "@/services/common/table";
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';


interface UpDatePasswordForm {
  id?: number;
  avatar?: string;
  nickname?: string;
  password?: string;
  rePassword: string;
}

const UpdateForm = (props: { record: any }) => {
  const { record } = props;
  const columns: ProFormColumnsAndProColumns<UpDatePasswordForm>[] = [
    {
      title: '用户ID',
      dataIndex: 'id',
      valueType: 'text',
      initialValue: record.id,
      readonly: true,
    },
    {
      title: '密码',
      dataIndex: 'password',
      valueType: 'password',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] }
    },
    {
      title: '确认密码',
      dataIndex: 'rePassword',
      valueType: 'password',
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

  /**
   * 更新节点
   * @param fields
   */
  const defaultUpdate = async (fields: UpDatePasswordForm) => {
    const hide = message.loading('正在更新');
    return editApi('/user/user/resetPassword', Object.assign({ user_id: record.id }, fields)).then(res => {
      if (res.success) {
        message.success('更新成功！');
        return true
      }
      return false
    }).finally(() => {
      hide()
    })
  }

  return (
    <BetaSchemaForm<UpDatePasswordForm>
      trigger={<a>重置密码</a>}
      title={'重置管理员密码'}
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
  )
}
export default UpdateForm;
