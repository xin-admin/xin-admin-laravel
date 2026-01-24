
export type ThemeScheme = "light" | "dark";

export type LayoutType = "side" | "top" | "mix" | "columns";

export interface ThemeStoreState {
  // 主题
  themeScheme: ThemeScheme;
  // 品牌色
  colorPrimary: string;
  // 主题侧栏开关
  themeDrawer: boolean;
  // 布局
  layout: LayoutType;
  // 侧栏展开
  collapsed: boolean;
  // 是否移动端
  isMobile: boolean;
}

export interface ThemeStoreActions {
  // 切换主题
  setTheme: (theme: ThemeScheme) => void;
  // 品牌色
  setColorPrimary: (color: string) => void;
  // 主题栏开关
  setThemeDrawer: (drawer: boolean) => void;
  // 导航侧栏开关
  setCollapsed: (open: boolean) => void;
  // 设置布局
  setLayout: (layout: LayoutType) => void;
  // 设置移动端
  setMobile: (mobile: boolean) => void;
}

export type ThemeStore = ThemeStoreState & ThemeStoreActions;
