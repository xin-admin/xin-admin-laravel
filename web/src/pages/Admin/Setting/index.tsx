import React, { useEffect, useRef, useState } from 'react';
import { BetaSchemaForm, ProCard, ProFormColumnsType, ProFormInstance } from '@ant-design/pro-components';
import { useModel } from '@umijs/max';
import { updateAdmin } from '@/services/admin';
import { Descriptions, message, Space, Tag, Timeline } from 'antd';
import UploadImgItem from '@/components/Xin/XinForm/UploadImgItem';
import { IAdminUserList } from '@/domain/iAdminList';
import UpdatePassword from './components/UpdatePassword';
import { listApi } from '@/services/common/table';
import { IAdminLoginLog } from '@/domain/iAdminLoginLog';
import UserInfoCard from './components/UserInfoCard';

const Table: React.FC = () => {
  const userInfo = useModel('userModel', (model) => model.userInfo);
  const formRef = useRef<ProFormInstance>();
  const [loginLogList, setLoginLogList] = useState<IAdminLoginLog[]>([]);
  useEffect(() => {
    listApi('admin/loginlog/my').then((res) => {
      setLoginLogList(res.data);
    });
  }, []);

  const columns: ProFormColumnsType<IAdminUserList>[] = [
    {
      title: '用户名',
      dataIndex: 'username',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      fieldProps: { disabled: true },
      colProps: { md: 7 },
    },
    {
      title: '昵称',
      dataIndex: 'nickname',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { md: 7 },
    },
    {
      title: '邮箱',
      dataIndex: 'email',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { md: 6 },
    },
    {
      title: '手机号',
      dataIndex: 'mobile',
      valueType: 'text',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      colProps: { md: 6 },
    },
    {
      title: '头像',
      dataIndex: 'avatar_id',
      valueType: 'avatar',
      formItemProps: { rules: [{ required: true, message: '该项为必填' }] },
      renderFormItem: (schema, config, form) => {
        return <UploadImgItem
          form={form}
          dataIndex={'avatar_id'}
          api={'/admin/uploadAvatar'}
          defaultFile={form.getFieldValue('avatar_url')}
          crop={true}
        />;
      },
      colProps: { md: 12 },
    },
  ];
  const submit = async (value: any) => {
    await updateAdmin(value);
    message.success('更新成功');
  };

  const tabsItem = [
    {
      label: '基本信息',
      key: 'tab0',
      children: <UserInfoCard userInfo={userInfo}/>
    },
    {
      label: '编辑资料',
      key: 'tab1',
      children: (
        <BetaSchemaForm<IAdminUserList>
          columns={columns}
          layoutType={'Form'}
          initialValues={{
            username: userInfo?.username,
            nickname: userInfo?.nickname,
            email: userInfo?.email,
            mobile: userInfo?.mobile,
            avatar_url: userInfo?.avatar_url,
            avatar_id: userInfo?.avatar_id,
          }}
          formRef={formRef}
          onFinish={submit}
          style={{ maxWidth: 400 }}
        />
      ),
    },
    {
      label: '修改密码',
      key: 'tab2',
      children: <UpdatePassword />,
    },
  ];

  return (
    <ProCard gutter={[20, 20]} ghost wrap>
      <ProCard
        bordered
        colSpan={{ md: 24, lg: 12, xl: 12, xxl: 8 }}
        headerBordered
        style={{ marginBottom: 10 }}
        bodyStyle={{ minHeight: 600 }}
        tabs={{ items: tabsItem }}
      />
      <ProCard colSpan={{ md: 24, lg: 12, xl: 12, xxl: 8 }} title={'登录日志'} bordered>
        <Timeline
          items={loginLogList.map((item) => {
            return {
              color: item.status === '0' ? 'green' : 'red',
              children: (
                <Space>{item.login_time}{item.login_location}{item.msg}</Space>
              ),
            };
          })}
        />
      </ProCard>
      <ProCard colSpan={{ md: 24, lg: 24, xl: 24, xxl: 8}} title={'权限字段'} bordered>
        <Space wrap>
          { userInfo?.rules?.map((item) => <Tag>{item}</Tag>) }
        </Space>
      </ProCard>
    </ProCard>
  );

};

export default Table;
