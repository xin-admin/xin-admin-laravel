import {createBrowserRouter, type DataRouteObject, Navigate} from "react-router";
import Layout from "@/layout";
import Login from "@/pages/login";
import React, {lazy, Suspense} from "react";
import type {IMenus} from "@/domain/iSysRule";
import defaultRoute from "@/router/default";

const modules = import.meta.glob('/src/pages/**/*')

function lazyLoad(path: string): React.ReactNode {
  if (modules[path]) {
    const Component = lazy(modules[path] as () => Promise<{ default: React.ComponentType }>);
    return (
      <Suspense fallback={null}>
        <Component />
      </Suspense>
    )
  }
  return null;
}

function buildRoute(menuData: IMenus[]): DataRouteObject[] {
  const routes: DataRouteObject[] = [];
  function traverse(nodes: IMenus[]) {
    for (const node of nodes) {
      // 处理 route 类型
      if (node.type === 'route' && !node.link) {
        // 检查是否有 nested-route 子节点
        const nestedChildren = node.children?.filter(child => child.type === 'nested-route') || [];
        if (nestedChildren.length > 0) {
          // 创建父路由
          const parentRoute: DataRouteObject = {
            id: node.key!,
            path: node.path,
            element: lazyLoad(`/src/pages${node.elementPath}.tsx`),
            children: []
          };
          // 添加 nested-route 子路由
          nestedChildren.forEach((child, index) => {
            if (index === 0) {
              parentRoute.children!.push({
                id: child.key,
                index: true,
                element: lazyLoad(`/src/pages${child.elementPath}.tsx`)
              });
            } else {
              parentRoute.children!.push({
                id: child.key,
                path: child.path,
                element: lazyLoad(`/src/pages${child.elementPath}.tsx`)
              });
            }
          });
          routes.push(parentRoute);
        } else {
          // 普通路由
          routes.push({
            id: node.key!,
            path: node.path,
            element: lazyLoad(`/src/pages${node.elementPath}.tsx`)
          });
        }
      }
      // 递归处理子节点
      if (node.children && node.children.length > 0) {
        traverse(node.children);
      }
    }
  }
  traverse(menuData);
  return routes;
}

export default function createRouter(rules?: IMenus[]) {
  let routes: DataRouteObject[];
  if(rules) {
    routes = buildRoute(rules);
  } else {
    routes = buildRoute(defaultRoute);
  }
  return createBrowserRouter([
    {
      Component: Layout,
      children: [
        ...routes,
        {
          path: "*",
          element: lazyLoad(`/src/pages/result/404.tsx`)
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
  ])
}