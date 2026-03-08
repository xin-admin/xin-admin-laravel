import type {LayoutType, ThemeProps} from "@/layout/typing.ts";

export interface GlobalStoreState {
  logo: string;
  title: string;
  subtitle: string;
  describe: string;
  layout: LayoutType;
  collapsed: boolean;
  themeConfig: ThemeProps;
  themeDrawer: boolean;
}

export interface GlobalStoreActions {
  setLayout: (layout: LayoutType) => void;
  setCollapsed: (collapsed: boolean) => void;
  initWebInfo: () => Promise<void>;
  setThemeConfig: (themeConfig: ThemeProps) => void;
  setThemeDrawer: (themeDrawer: boolean) => void;
}

export type GlobalStore = GlobalStoreState & GlobalStoreActions;