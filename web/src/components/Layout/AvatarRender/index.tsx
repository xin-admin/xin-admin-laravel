import { Avatar, type AvatarProps, Dropdown, DropdownProps } from 'antd';
import { LogoutOutlined, UserOutlined } from '@ant-design/icons';
import { useModel, useNavigate } from '@umijs/max';
import React from 'react';
import {logout as logoutApi } from '@/services';

type AvatarRenderType = (props: AvatarProps, defaultDom: React.ReactNode) => React.ReactNode

/**
 * 头像渲染
 * @constructor
 */
const AvatarRender: AvatarRenderType = () => {
  const {isLogin, userInfo} = useModel('userModel', ({isLogin, userInfo}) => {
    return {isLogin, userInfo}
  });
  let navigate = useNavigate();
  const logout =  async () => {
    await logoutApi();
    localStorage.clear();
    window.location.href = '/login';
  }

  const dropItem: DropdownProps['menu']  =  {
    items: [
      {
        key: 'user',
        icon: <UserOutlined />,
        label: '用户设置',
        onClick: () => navigate('/admin/setting')
      },
      {
        key: 'logout',
        icon: <LogoutOutlined />,
        label: '退出登录',
        onClick: logout,
      },
    ]
  }

  return (
    <>
      { isLogin ?
        <Dropdown menu={dropItem}>
          <div style={{display: 'flex', alignItems: 'center'}}>
            <Avatar src={userInfo?.avatar_url} style={{marginRight: '10px'}} />
            { userInfo?.nickname || userInfo?.username || "" }
          </div>
        </Dropdown>
        :
        null
      }
    </>
  )
}

export default AvatarRender;
