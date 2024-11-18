import { Avatar, type AvatarProps, Button, Dropdown, DropdownProps, Space } from 'antd';
import { LogoutOutlined, UserOutlined } from '@ant-design/icons';
import { Logout as UserLogout } from '@/services/api/user';
import { Logout as AdminLogout } from '@/services/admin';
import { useModel, useNavigate } from '@umijs/max';
import React from 'react';

type AvatarRenderType = (props: AvatarProps, defaultDom: React.ReactNode) => React.ReactNode

/**
 * 头像渲染
 * @constructor
 */
const AvatarRender: AvatarRenderType = () => {
  const {initialState} = useModel('@@initialState');
  let navigate = useNavigate();
  const logout =  async () => {
    if(initialState?.app === 'admin' && localStorage.getItem('x-token')) {
      await AdminLogout();
      localStorage.clear();
      window.location.href = '/';
    }else if(initialState?.app === 'api' && localStorage.getItem('x-user-token')){
      await UserLogout();
      localStorage.clear();
      window.location.href = '/';
    }else {
      localStorage.clear();
      window.location.href = '/';
    }
  }

  const dropItem = (): DropdownProps['menu']  => {
    let data: DropdownProps['menu'] = {
      items: [
        {
          key: 'logout',
          icon: <LogoutOutlined />,
          label: '退出登录',
          onClick: logout,
        },
      ],
    }
    if(initialState!.app === 'admin') {
      data.items!.unshift({
        key: 'user',
        icon: <UserOutlined/>,
        label: '用户设置',
        onClick: () => navigate('/admin/setting')
      })
    }
    return data
  }

  return (
    <div>
      { initialState?.isLogin ?
        <Dropdown menu={dropItem()}>
          <div style={{display: 'flex', alignItems: 'center'}}>
            <Avatar src={initialState.currentUser?.avatar_url} style={{marginRight: '10px'}} />
            { initialState.currentUser?.nickname || initialState.currentUser?.username || "" }
          </div>
        </Dropdown>
        :
        <>
          <Button type={'link'} onClick={() => navigate('/client/login')}>登录</Button>
          <Button type={'link'} onClick={() => navigate('/client/reg')}>注册</Button>
        </>
      }
    </div>
  )
}

export default AvatarRender;