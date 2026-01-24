/**
 * 主题 Slice
 * 管理主题配置和主题抽屉状态
 */

import type { StateCreator } from 'zustand';
import type { GlobalStore, ThemeState, ThemeAction } from '@/stores/types';
import type { ThemeProps } from "@/layout/typing";
import { configTheme, defaultColorTheme } from "@/layout/theme";

export type ThemeSlice = ThemeState & ThemeAction;

/**
 * 主题初始状态
 */
export const initialThemeState: ThemeState = {
  themeConfig: { ...defaultColorTheme, ...configTheme },
  themeDrawer: false,
};

/**
 * 创建主题 Slice
 */
export const createThemeSlice: StateCreator<GlobalStore, [], [], ThemeSlice> = (set) => ({
  ...initialThemeState,
  
  setThemeConfig: (themeConfig: ThemeProps) => {
    set({ themeConfig });
  },
  
  setThemeDrawer: (themeDrawer: boolean) => {
    set({ themeDrawer });
  },
});
