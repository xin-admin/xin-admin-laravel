import React, { useEffect, useState } from 'react';
import { ProCard } from '@ant-design/pro-components';
import { useModel } from '@umijs/max';
import { Space, Tag, Timeline } from 'antd';
import UpdatePassword from './components/UpdatePassword';
import { listApi } from '@/services/common/table';
import { IAdminLoginLog } from '@/domain/iAdminLoginLog';
import UserInfoCard from './components/UserInfoCard';
import EditUserInfo from '@/pages/Admin/Setting/components/EditUserInfo';

const Table: React.FC = () => {

  const userInfo = useModel('userModel', (model) => model.userInfo);
  const [loginLogList, setLoginLogList] = useState<IAdminLoginLog[]>([]);

  useEffect(() => {
    listApi('admin/loginlog/my').then((res) => {
      setLoginLogList(res.data);
    });
  }, []);

  return (
    <ProCard gutter={[20, 20]} ghost wrap>
      <ProCard
        bordered
        headerBordered
        title={'个人资料'}
        style={{ marginBottom: 10 }}
        colSpan={{ md: 24, lg: 12, xl: 12, xxl: 8 }}
        bodyStyle={{ minHeight: 'calc(100vh - 200px)' }}
        extra={
          <Space>
            <EditUserInfo />
            <UpdatePassword />
          </Space>
        }
      >
        <UserInfoCard userInfo={userInfo} />
      </ProCard>
      <ProCard
        bordered
        headerBordered
        title={'登录日志'}
        colSpan={{ md: 24, lg: 12, xl: 12, xxl: 8 }}
        bodyStyle={{ minHeight: 'calc(100vh - 200px)' }}
      >
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
      <ProCard
        bordered
        headerBordered
        title={'权限字段'}
        bodyStyle={{ minHeight: 'calc(100vh - 200px)' }}
        colSpan={{ md: 24, lg: 24, xl: 24, xxl: 8 }}
      >
        <Space wrap>
          {userInfo?.rules?.map((item) => <Tag>{item}</Tag>)}
        </Space>
      </ProCard>
    </ProCard>
  );

};

export default Table;
