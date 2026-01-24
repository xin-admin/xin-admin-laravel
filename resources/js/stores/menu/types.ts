import type {IMenus} from "@/domain/iSysRule.ts";
import type {BreadcrumbItem} from "@/stores";

export interface MenuStoreState {
  menus: IMenus[];
  menuMap: Record<string, IMenus>;
  breadcrumbMap: Record<string, BreadcrumbItem[]>;
}

export interface MenuStoreActions {
  menu: () => Promise<void>;
  setMenus: (menus: IMenus[]) => void;
}

export type MenuStore = MenuStoreState & MenuStoreActions;