/**
 * Global Store
 * 组合所有全局状态 Slices
 */

import { create } from 'zustand';
import { devtools, persist, createJSONStorage } from 'zustand/middleware';
import type { GlobalStore } from '../types';
import { createSiteSlice, initialSiteState } from './slices/site';
import { createLayoutSlice, initialLayoutState } from './slices/layout';
import { createThemeSlice, initialThemeState } from './slices/theme';

/**
 * Global Store 初始状态
 * 用于重置或测试
 */
export const initialGlobalState = {
  ...initialSiteState,
  ...initialLayoutState,
  ...initialThemeState,
};

/**
 * 需要持久化的状态字段
 * 排除临时性状态如 mobileMenuOpen, themeDrawer, breadcrumb
 */
const persistedKeys: (keyof typeof initialGlobalState)[] = [
  'layout',
  'themeConfig',
  'menuParentKey',
];

/**
 * Global Store
 */
export const useGlobalStore = create<GlobalStore>()(
  devtools(
    persist(
      (...args) => ({
        ...createSiteSlice(...args),
        ...createLayoutSlice(...args),
        ...createThemeSlice(...args),
      }),
      {
        name: 'global-store-storage',
        storage: createJSONStorage(() => localStorage),
        // 只持久化需要保存的状态，排除临时状态
        partialize: (state) => {
          const persisted: Partial<typeof initialGlobalState> = {};
          for (const key of persistedKeys) {
            if (key in state) {
              (persisted as Record<string, unknown>)[key] = state[key as keyof GlobalStore];
            }
          }
          return persisted;
        },
      }
    ),
    { name: 'XinAdmin-Global' }
  )
);

/**
 * 导出类型供外部使用
 */
export type { GlobalStore } from '../types';
