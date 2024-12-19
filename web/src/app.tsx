import React, { lazy } from 'react';
import { Navigate, RuntimeConfig, RunTimeLayoutConfig } from '@umijs/max';
import defaultRoutes from '@/default/routes';
import settings from '@/default/settings';
import defaultConfig from '@/utils/request';
import appList from '@/default/appList';
import footerRender from '@/components/Layout/FooterRender';
import menuFooterRender from '@/components/Layout/MenuFooterRender';
import actionsRender from '@/components/Layout/ActionsRender';
import AvatarRender from '@/components/Layout/AvatarRender';
import headerTitleRender from '@/components/Layout/HeaderTitleRender';
import headerContentRender from '@/components/Layout/HeaderContentRender';
import menuRender from '@/components/Layout/MenuRender';
import Access from '@/components/Access';
import './app.less';

// 全局初始化状态
export async function getInitialState(): Promise<initialStateType> {
  return {
    drawerShow: false,
    collapsed: false,
    settings: settings,
    borderShow: true,
    webSetting: {
      logo: 'https://file.xinadmin.cn/file/favicons.ico',
      title: 'Xin Admin'
    }
  };
}

export const layout: RunTimeLayoutConfig = ({ initialState }) => {
  return {
    footerRender, // 页面底部
    actionsRender, // 操作列表
    menuFooterRender, // 导航栏底部
    headerContentRender, // 头部内容区域
    headerTitleRender, // 头部标题区域
    avatarProps: { render: () => <AvatarRender/>, }, // 头像
    appList, // 应用列表
    menuRender, // 菜单
    collapsedButtonRender: false, // 隐藏默认侧栏
    collapsed: initialState?.collapsed, // 侧栏展开状态
    // childrenRender: (children: any) => <Access>{children}</Access>, // 页面内容
    ...initialState?.settings,
  }
}

export const onRouteChange: RuntimeConfig['onRouteChange'] = ({location}) => {
  if(location.pathname != '/login' && !localStorage.getItem('x-token')) window.location.href = '/login';
}

// 修改被 react-router 渲染前的树状路由表，接收内容同 useRoutes
export const patchClientRoutes: RuntimeConfig['patchClientRoutes'] = ({ routes }) => {
  const lazyLoad = (moduleName: string) => {
    const Module = lazy(() => import(`./pages/${moduleName}`));
    return <Module />;
  };
  routes.unshift({
    path: '/',
    element: <Navigate to="/dashboard/analysis" replace />,
  });
  routes.push(...defaultRoutes(lazyLoad));
};

// request 配置
export const request = { ...defaultConfig };
