import React, { lazy } from 'react';
import { RuntimeConfig, RunTimeLayoutConfig } from '@umijs/max';
import { history, Navigate } from '@umijs/max';
import defaultRoutes from '@/default/routes';
import defaultInitialState from '@/default/initialState';
import { adminSettings, appSettings, Settings } from '@/default/settings';
import { index } from '@/services/api';
import defaultConfig from '@/utils/request';
import menuDataRender from '@/utils/menuDataRender';
import Footer from '@/components/Footer';
import Access from '@/components/Access';
import SettingLayout from '@/components/SettingDrawer';
import ActionsRender from '@/components/Layout/ActionsRender';
import AvatarRender from '@/components/Layout/AvatarRender';
import XinTabs from '@/components/XinTabs';
import './app.less';
import menuItemRender from "@/utils/menuItemRender";
import subMenuItemRender from "@/utils/subMenuItemRender";

// 全局初始化状态
export async function getInitialState(): Promise<initialStateType> {
  const { location } = history;
  const data: initialStateType = defaultInitialState;
  let indexDate = await index();
  data.webSetting = indexDate.data.web_setting;
  data.menus = indexDate.data.menus;
  if (
    location.pathname === '/admin/login' ||
    location.pathname === '/client/login' ||
    location.pathname === '/client/reg'
  ) {
    return data;
  }
  let userInfo;
  if (localStorage.getItem('x-token') && data.app === 'admin') {
    userInfo = await data.fetchAdminInfo();
    data.settings = adminSettings;
    data.isLogin = true;
    data.currentUser = userInfo.info;
    data.menus = userInfo.menus;
    data.access = userInfo.access;
    data.app = 'admin';
    localStorage.setItem('app', 'admin');
    return data;
  }
  if (localStorage.getItem('x-user-token') && data.app === 'api') {
    userInfo = await data.fetchUserInfo();
    data.settings = appSettings;
    data.isLogin = true;
    data.currentUser = userInfo.info;
    data.menus = userInfo.menus;
    data.access = userInfo.access;
    data.app = 'api';
    localStorage.setItem('app', 'api');
    return data;
  }
  return data;
}

export const layout: RunTimeLayoutConfig = ({ initialState }) => {
  return Object.assign(Settings, {
    logo: initialState?.webSetting?.logo,
    title: initialState?.webSetting?.title,
    menu: { request: async () => initialState?.menus },
    menuDataRender,
    menuItemRender,
    subMenuItemRender,
    footerRender: () => <Footer />,
    actionsRender: ActionsRender,
    avatarProps: {
      render: () => <AvatarRender />,
    },
    childrenRender: (children: any) => {
      if (initialState?.app === 'admin') return <XinTabs><Access><SettingLayout />{children}</Access></XinTabs>;
      return <Access>{children}</Access>;
    },
    ...initialState?.settings,
  })
}

// 修改被 react-router 渲染前的树状路由表，接收内容同 useRoutes
export const patchClientRoutes: RuntimeConfig['patchClientRoutes'] = ({ routes }) => {
  console.log('patchClientRoutes')
  const lazyLoad = (moduleName: string) => {
    const Module = lazy(() => import(`./pages/${moduleName}`));
    return <Module />;
  };
  if (localStorage.getItem('app') === 'admin') {
    routes.unshift({
      path: '/',
      element: <Navigate to="/dashboard/analysis" replace />,
    });
  }
  routes.push(...defaultRoutes(lazyLoad));
};

// request 配置
export const request = { ...defaultConfig };
