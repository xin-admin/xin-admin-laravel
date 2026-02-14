import {createBrowserRouter, type DataRouteObject, Navigate} from "react-router";
import Layout from "@/layout";
import Login from "@/pages/login";
import React, {lazy, Suspense} from "react";

const modules = import.meta.glob('/web/pages/**/*.tsx');

// 排除的路径（不需要在 Layout 内渲染的页面）
const excludePaths = ['/login'];

/**
 * 懒加载组件
 */
function lazyLoad(moduleFn: () => Promise<{ default: React.ComponentType }>): React.ReactNode {
  const Component = lazy(moduleFn as () => Promise<{ default: React.ComponentType }>);
  return (
    <Suspense fallback={null}>
      <Component />
    </Suspense>
  );
}

/**
 * 基于文件系统构建路由
 */
function buildRoutesFromFiles(): DataRouteObject[] {
  const routes: DataRouteObject[] = [];

  for (const path in modules) {
    // 转换路径
    let routePath = path
      .replace('/web/pages', '')
      .replace(/\.tsx$/, '')
      .replace(/\/index$/, '');  // index.tsx 映射到父路径

    // 空路径设为根路径
    if (!routePath) {
      routePath = '/';
    }

    // 跳过排除的路径
    if (excludePaths.includes(routePath)) {
      continue;
    }

    routes.push({
      id: routePath,
      path: routePath,
      element: lazyLoad(modules[path] as () => Promise<{ default: React.ComponentType }>)
    });
  }

  return routes;
}

export default function createRouter() {
  const routes = buildRoutesFromFiles();
  return createBrowserRouter([
    {
      Component: Layout,
      children: [
        ...routes,
        {
          path: "*",
          element: lazyLoad(modules['/web/pages/result/404.tsx'] as () => Promise<{ default: React.ComponentType }>)
        }
      ],
    },
    {
      path: '/',
      element: <Navigate to="/dashboard/analysis"/>
    },
    {
      path: "login",
      element: <Login />
    },
  ]);
}
