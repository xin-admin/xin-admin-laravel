interface initialStateType {
  // 布局设置
  settings?: any;
  // 加载状态
  loading?: boolean;
  // 全局样式
  borderShow?: boolean;
  // 菜单设置
  drawerShow?: boolean;
  // 当前app
  app?: 'api' | 'admin';
  // 其它
  webSetting?: { [key: string] : any };
  // 侧栏展开状态
  collapsed?: boolean;
}

interface Menus {
  children: Menus[];
  create_time: string;
  icon: string;
  id: number;
  key: string;
  name: string;
  path: string;
  pid: number;
  remark: string;
  sort: number;
  type: string;
  local: string;
  update_time: string;
}
