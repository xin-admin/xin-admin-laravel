import React, { useEffect, useRef, useState } from 'react';
import { BetaSchemaForm, ProCard, ProFormColumnsType, ProFormInstance } from '@ant-design/pro-components';
import { useModel } from '@umijs/max';
import { updateAdmin } from '@/services/admin';
import { Descriptions, message, Timeline } from 'antd';
import UploadImgItem from '@/components/Xin/XinForm/UploadImgItem';
import { IAdminUserList } from '@/domain/iAdminList';
import UpdatePassword from './components/UpdatePassword';
import { listApi } from '@/services/common/table';
import { IAdminLoginLog } from '@/domain/iAdminLoginLog';

const Table: React.FC = () => {
  let userInfo = useModel('userModel', (model) => model.userInfo);
  const formRef = useRef<ProFormInstance>();
  const [loginLogList, setLoginLogList] = useState<IAdminLoginLog[]>([]);
  useEffect(() => {
    if (userInfo) {
      formRef.current?.setFieldsValue({
        username: userInfo.username,
        nickname: userInfo.nickname,
        email: userInfo.email,
        mobile: userInfo.mobile,
        avatar_url: userInfo.avatar_url,
        avatar_id: userInfo.avatar_id,
      });
    }
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
      key: 'tab1',
      children: (
        <BetaSchemaForm<IAdminUserList>
          columns={columns}
          layoutType={'Form'}
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
    <ProCard gutter={20} ghost>
      <ProCard
        headerBordered
        style={{ marginBottom: 10 }}
        tabs={{ items: tabsItem }}
      />
      <ProCard title={'登录日志'} headerBordered>
        <Timeline
          items={loginLogList.map((item) => {
            return {
              color: item.status === '0' ? 'green' : 'red',
              children: (
                <Descriptions
                  style={{ marginBottom: 10, paddingBottom: 10, borderBottom: "1px solid #eee" }}
                  items={[
                    {
                      key: 'ipaddr',
                      label: 'IP地址',
                      children: item.ipaddr,
                    },
                    {
                      key: 'msg',
                      label: '登录消息',
                      children: item.msg,
                    },
                    {
                      key: 'browser',
                      label: '浏览器',
                      children: item.browser,
                    },
                    {
                      key: 'os',
                      label: '操作系统',
                      children: item.os,
                    },
                    {
                      key: 'login_time',
                      label: '登录时间',
                      children: item.login_time,
                    },
                  ]}
                />
              ),
            };
          })}
        />
      </ProCard>
    </ProCard>
  );

};

export default Table;
