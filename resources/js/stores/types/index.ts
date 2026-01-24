/**
 * Store 共享类型定义
 */
import type { LayoutType, ThemeProps } from "@/layout/typing";
import type { IMenus } from "@/domain/iSysRule";
import type ISysUser from "@/domain/iSysUser";
import type { LoginParams } from "@/api/sys/sysUser";

// ============== 共享类型 ==============

/**
 * 面包屑类型
 */
export interface BreadcrumbItem {
  href?: string;
  title?: string;
  icon?: string;
  local?: string;
}

/**
 * 菜单索引映射
 */
export interface MenuIndexes {
  menuMap: Record<string, IMenus>;
  breadcrumbMap: Record<string, BreadcrumbItem[]>;
}

// ============== Global Store 类型 ==============

/**
 * 网站基础信息状态
 */
export interface SiteState {
  logo: string;
  title: string;
  subtitle: string;
  describe: string;
  documentTitle: string;
}

/**
 * 网站基础信息操作
 */
export interface SiteAction {
  initWebInfo: () => Promise<void>;
  setDocumentTitle: (documentTitle: string) => void;
}

/**
 * 布局状态
 */
export interface LayoutState {
  layout: LayoutType;
  collapsed: boolean;
  isMobile: boolean;
  mobileMenuOpen: boolean;
  breadcrumb: BreadcrumbItem[];
  menuParentKey: string | null;
}

/**
 * 布局操作
 */
export interface LayoutAction {
  setLayout: (layout: LayoutType) => void;
  setCollapsed: (collapsed: boolean) => void;
  setIsMobile: (isMobile: boolean) => void;
  setMobileMenuOpen: (mobileMenuOpen: boolean) => void;
  setBreadcrumb: (breadcrumb: BreadcrumbItem[]) => void;
  setMenuParentKey: (menuParentKey: string) => void;
}

/**
 * 主题状态
 */
export interface ThemeState {
  themeConfig: ThemeProps;
  themeDrawer: boolean;
}

/**
 * 主题操作
 */
export interface ThemeAction {
  setThemeConfig: (themeConfig: ThemeProps) => void;
  setThemeDrawer: (themeDrawer: boolean) => void;
}

/**
 * Global Store 完整类型
 */
export type GlobalState = SiteState & LayoutState & ThemeState;
export type GlobalAction = SiteAction & LayoutAction & ThemeAction;
export type GlobalStore = GlobalState & GlobalAction;

// ============== Auth Store 类型 ==============

/**
 * 用户状态
 */
export interface UserState {
  user: ISysUser | null;
  access: string[];
}

/**
 * 用户操作
 */
export interface UserAction {
  setAccess: (access: string[]) => void;
}

/**
 * 菜单状态
 */
export interface MenuState {
  menus: IMenus[];
  menuMap: Record<string, IMenus>;
  breadcrumbMap: Record<string, BreadcrumbItem[]>;
  localRoute: boolean;
}

/**
 * 菜单操作
 */
export interface MenuAction {
  setMenus: (menus: IMenus[]) => void;
  setLocalRoute: (isLocal: boolean) => void;
}

/**
 * 认证状态
 */
export interface AuthenticationState {
  initialized: boolean;
}

/**
 * 认证操作
 */
export interface AuthenticationAction {
  login: (credentials: LoginParams) => Promise<boolean>;
  logout: () => Promise<void>;
  getInfo: () => Promise<void>;
  initApp: () => Promise<void>;
  isAuthenticated: () => boolean;
}

/**
 * Auth Store 完整类型
 */
export type AuthState = UserState & MenuState & AuthenticationState;
export type AuthAction = UserAction & MenuAction & AuthenticationAction;
export type AuthStore = AuthState & AuthAction;

// ============== Store 辅助类型 ==============

/**
 * Zustand StateCreator 辅助类型
 */
export type SetState<T> = (
  partial: T | Partial<T> | ((state: T) => T | Partial<T>),
  replace?: boolean
) => void;

export type GetState<T> = () => T;

/**
 * Slice 创建函数类型
 */
export type SliceCreator<T, S = T> = (
  set: SetState<S>,
  get: GetState<S>
) => T;
