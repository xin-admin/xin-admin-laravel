/**
 * 布局 Slice
 * 管理布局类型、侧边栏折叠、移动端状态等
 */

import type { StateCreator } from 'zustand';
import type { GlobalStore, LayoutState, LayoutAction, BreadcrumbItem } from '@/stores/types';
import type { LayoutType } from "@/layout/typing";

export type LayoutSlice = LayoutState & LayoutAction;

/**
 * 布局初始状态
 */
export const initialLayoutState: LayoutState = {
  layout: "side",
  collapsed: false,
  isMobile: false,
  mobileMenuOpen: false,
  breadcrumb: [],
  menuParentKey: null,
};

/**
 * 创建布局 Slice
 */
export const createLayoutSlice: StateCreator<GlobalStore, [], [], LayoutSlice> = (set) => ({
  ...initialLayoutState,
  
  setLayout: (layout: LayoutType) => {
    set({ layout });
  },
  
  setCollapsed: (collapsed: boolean) => {
    set({ collapsed });
  },
  
  setIsMobile: (isMobile: boolean) => {
    set({ isMobile });
  },
  
  setMobileMenuOpen: (mobileMenuOpen: boolean) => {
    set({ mobileMenuOpen });
  },
  
  setBreadcrumb: (breadcrumb: BreadcrumbItem[]) => {
    set({ breadcrumb });
  },
  
  setMenuParentKey: (menuParentKey: string) => {
    set({ menuParentKey });
  },
});
