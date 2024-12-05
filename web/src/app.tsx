import React, { lazy } from 'react';
import { RuntimeConfig, RunTimeLayoutConfig } from '@umijs/max';
import { history, Navigate } from '@umijs/max';
import defaultRoutes from '@/default/routes';
import defaultInitialState from '@/default/initialState';
import { adminSettings, appSettings, Settings } from '@/default/settings';
import { index } from '@/services/api';
import defaultConfig from '@/utils/request';
import menuDataRender from '@/utils/menuDataRender';
import footerRender from '@/components/Layout/FooterRender';
import menuFooterRender from '@/components/Layout/MenuFooterRender';
import actionsRender from '@/components/Layout/ActionsRender';
import AvatarRender from '@/components/Layout/AvatarRender';
import headerTitleRender from '@/components/Layout/HeaderTitleRender';
import headerContentRender from '@/components/Layout/HeaderContentRender';
import Access from '@/components/Access';
import SettingLayout from '@/components/SettingDrawer';
import './app.less';
// import menuItemRender from "@/utils/menuItemRender";
// import subMenuItemRender from "@/utils/subMenuItemRender";
import appList from '@/default/appList';

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
    footerRender,
    actionsRender,
    menuFooterRender,
    headerContentRender,
    headerTitleRender,
    avatarProps: {
      render: () => <AvatarRender/>,
    },
    appList,
    // logo: initialState?.webSetting?.logo,
    // title: initialState?.webSetting?.title,
    menu: { request: async () => initialState?.menus },
    menuDataRender,
    // menuItemRender, // 为了解决性能问题，暂时移除自定义，等Umi官方修复
    // subMenuItemRender,
    collapsedButtonRender: false, // 隐藏默认侧栏
    collapsed: initialState?.collapsed, // 侧栏展开状态
    childrenRender: (children: any) => {
      if (initialState?.app === 'admin') return <Access><SettingLayout />{children}</Access>;
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
