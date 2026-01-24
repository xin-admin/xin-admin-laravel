/**
 * Global Store Selectors
 * 提供可复用的状态选择器，避免不必要的重渲染
 */

import type { GlobalStore } from '../types';

// ============== Site Selectors ==============

export const siteSelectors = {
  /** 获取网站 Logo */
  logo: (state: GlobalStore) => state.logo,
  /** 获取网站标题 */
  title: (state: GlobalStore) => state.title,
  /** 获取网站副标题 */
  subtitle: (state: GlobalStore) => state.subtitle,
  /** 获取网站描述 */
  describe: (state: GlobalStore) => state.describe,
  /** 获取文档标题 */
  documentTitle: (state: GlobalStore) => state.documentTitle,
  /** 获取网站基础信息 */
  siteInfo: (state: GlobalStore) => ({
    logo: state.logo,
    title: state.title,
    subtitle: state.subtitle,
    describe: state.describe,
  }),
};

// ============== Layout Selectors ==============

export const layoutSelectors = {
  /** 获取布局类型 */
  layout: (state: GlobalStore) => state.layout,
  /** 获取侧边栏折叠状态 */
  collapsed: (state: GlobalStore) => state.collapsed,
  /** 获取移动端状态 */
  isMobile: (state: GlobalStore) => state.isMobile,
  /** 获取移动端菜单状态 */
  mobileMenuOpen: (state: GlobalStore) => state.mobileMenuOpen,
  /** 获取面包屑 */
  breadcrumb: (state: GlobalStore) => state.breadcrumb,
  /** 获取菜单父级 Key */
  menuParentKey: (state: GlobalStore) => state.menuParentKey,
  /** 获取布局配置 */
  layoutConfig: (state: GlobalStore) => ({
    layout: state.layout,
    collapsed: state.collapsed,
    isMobile: state.isMobile,
  }),
};

// ============== Theme Selectors ==============

export const themeSelectors = {
  /** 获取主题配置 */
  themeConfig: (state: GlobalStore) => state.themeConfig,
  /** 获取主题抽屉状态 */
  themeDrawer: (state: GlobalStore) => state.themeDrawer,
  /** 获取主题方案 */
  themeScheme: (state: GlobalStore) => state.themeConfig.themeScheme,
  /** 获取主色调 */
  colorPrimary: (state: GlobalStore) => state.themeConfig.colorPrimary,
};

// ============== 组合 Selectors ==============

export const globalSelectors = {
  ...siteSelectors,
  ...layoutSelectors,
  ...themeSelectors,
};
