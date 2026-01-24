import {create, type StateCreator} from "zustand";
import {createJSONStorage, devtools, persist} from "zustand/middleware";
import type {ThemeScheme, ThemeStore, ThemeStoreState, LayoutType} from "./types";

const initialState: ThemeStoreState = {
  themeScheme: 'light',
  colorPrimary: '',
  themeDrawer: false,
  layout: 'side',
  collapsed: true,
  isMobile: false
}

const createThemeStore: StateCreator<ThemeStore> = (set) => ({
  ...initialState,

  setTheme: (themeScheme) => set({themeScheme}),

  setCollapsed: (collapsed) => set({collapsed}),

  setColorPrimary: (colorPrimary) => set({colorPrimary}),

  setLayout: (layout) => set({layout}),

  setThemeDrawer: (themeDrawer) => set({themeDrawer}),

  setMobile: (isMobile) => set({isMobile}),
})

const useMenuStore = create<ThemeStore>()(
  devtools(
    persist(
      createThemeStore,
      {
        name: 'theme-storage',
        storage: createJSONStorage(() => localStorage),
      }
    ),
    { name: 'XinAdmin-Theme' }
  )
);

export type { ThemeStore, ThemeStoreState, ThemeScheme, LayoutType };
export default useMenuStore;