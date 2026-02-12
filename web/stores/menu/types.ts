import type {IMenus} from "@/domain/iSysRule.ts";

export interface BreadcrumbItem {
  href?: string;
  title?: string;
  icon?: string;
  local?: string;
}

export interface MenuStoreState {
  menus: IMenus[];
  selectKey: string[];
  routeMap: Record<string, IMenus>;
  pathMap: Record<string, string>;
  parentKeyMap: Record<string, string[]>;
  breadcrumbMap: Record<string, BreadcrumbItem[]>;
}

export interface MenuStoreActions {
  menu: () => Promise<IMenus[]>;
  setMenus: (menus: IMenus[]) => void;
  setSelectKey: (selectKey: string[]) => void;
}

export type MenuStore = MenuStoreState & MenuStoreActions;