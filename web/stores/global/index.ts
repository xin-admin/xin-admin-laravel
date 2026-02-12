import {create, type StateCreator} from 'zustand';
import {createJSONStorage, devtools, persist} from "zustand/middleware";
import type {GlobalStoreState, GlobalStoreActions, GlobalStore} from "./types";
import {configTheme, defaultColorTheme} from "@/layout/theme.ts";
import type {LayoutType, ThemeProps} from "@/layout/typing.ts";
import {getWebInfo} from "@/api";

const globalState: GlobalStoreState = {
  logo: "https://file.xinadmin.cn/file/favicons.ico",
  title: "Xin Admin",
  subtitle: "基于 Ant Design 的后台管理框架",
  describe: "Xin Admin 是一个基于 Ant Design 的后台管理框架",
  layout: "side",
  collapsed: false,
  themeConfig: { ...defaultColorTheme, ...configTheme },
  themeDrawer: false,
};

const globalAction: StateCreator<GlobalStoreState, [], [], GlobalStoreActions> = (set) => ({
  initWebInfo: async () => {
    try {
      const response = await getWebInfo();
      if (response.data.data) {
        set(response.data.data);
      }
    } catch (error) {
      console.error('获取网站信息失败:', error);
    }
  },
  setLayout: (layout: LayoutType) => {
    set({ layout });
  },
  setCollapsed: (collapsed: boolean) => {
    set({ collapsed });
  },
  setThemeConfig: (themeConfig: ThemeProps) => {
    set({ themeConfig });
  },

  setThemeDrawer: (themeDrawer: boolean) => {
    set({ themeDrawer });
  },
})

const useGlobalStore = create<GlobalStore>()(
  devtools(
    persist(
      (...args) => ({
        ...globalState,
        ...globalAction(...args),
      }),
      {
        name: 'global-storage',
        storage: createJSONStorage(() => localStorage),
      }
    ),
    { name: 'XinAdmin-Global' }
  )
);

export type {GlobalStore, GlobalStoreState, GlobalStoreActions};
export default useGlobalStore;