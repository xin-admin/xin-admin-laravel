/**
 * 基础路由
 * @param lazyLoad
 */
export default (lazyLoad: (moduleName: string) => JSX.Element) => {
  return [
    {
      name: '登录',
      path: 'admin/login',
      id: 'adminLogin',
      element: lazyLoad('backend/Login'),
      layout: false,
    },
  ];
}
