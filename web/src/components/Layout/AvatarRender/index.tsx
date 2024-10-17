import { Avatar, Button, Dropdown, DropdownProps, Modal } from 'antd';
import { LogoutOutlined, UserOutlined } from '@ant-design/icons';
import { Logout as UserLogout } from '@/services/api/user';
import { Logout as AdminLogout } from '@/services/admin';
import { index } from '@/services/api';
import { useModel, useNavigate } from '@umijs/max';

export default () => {
  const {initialState,setInitialState} = useModel('@@initialState');

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
  let navigate = useNavigate();
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
    <>
      { initialState?.isLogin ?
        <Dropdown
          menu={dropItem()}
        >
          <Avatar src={initialState.currentUser?.avatar_url}/>
        </Dropdown>
        :
        <>
          <Button type={'link'} onClick={() => navigate('/client/login')}>登录</Button>
          <Button type={'link'} onClick={() => navigate('/client/reg')}>注册</Button>
        </>
      }
    </>

  )
}
