/**
 * 基础路由
 * @param lazyLoad
 */
export default (lazyLoad: (moduleName: string) => JSX.Element) => {
  return [
    {
      name: '登录',
      path: '/login',
      id: 'adminLogin',
      element: lazyLoad('Admin/components/Login'),
      layout: false,
    },
  ];
}
