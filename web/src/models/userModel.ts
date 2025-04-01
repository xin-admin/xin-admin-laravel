import { useBoolean } from 'ahooks';
import { useEffect, useState } from 'react';
import { getAdminInfo } from '@/services';
import { IAdminUserList } from '@/domain/iAdminList';
import { IRule } from '@/domain/iRule';
const useUser = () => {

  // 登录状态
  const [isLogin, setLoginStatus] = useBoolean();
  // 用户信息
  const [userInfo, setUserInfo] = useState<IAdminUserList>();
  // 用户权限
  const [userAccess, setUserAccess] = useState<string[]>([]);
  // 菜单
  const [menus, setMenus] = useState<IRule[]>([]);

  // 获取用户信息
  const getUser = async () => {
    let token = localStorage.getItem('token');
    if(!token) {
      return Promise.reject();
    }
    let {data} = await getAdminInfo();
    setUserInfo(data.info);
    setUserAccess(data.access.map(item => item.toLowerCase()));
    setMenus(data.menus);
    localStorage.setItem('menus', JSON.stringify(data.menus));
    setLoginStatus.setTrue();
  }

  // 刷新用户信息
  const refreshUserInfo =  () => {
    getUser().catch(() => {
      setUserInfo({});
      setUserAccess([]);
      setMenus([]);
    })
  }

  //异步刷新用户信息
  const refreshUserInfoAsync =  async () => {
    try {
      await getUser()
    }catch (error) {
      setUserInfo({});
      setUserAccess([]);
      setMenus([]);
    }
  }

  useEffect(refreshUserInfo,[])

  return {
    isLogin,
    userInfo,
    userAccess,
    menus,
    refreshUserInfo,
    refreshUserInfoAsync
  }
}

export default useUser;
