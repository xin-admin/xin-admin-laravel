import {create, type StateCreator} from 'zustand';
import {createJSONStorage, devtools, persist} from "zustand/middleware";
import {menu} from "@/api/sys/sysUser.ts";
import type {IMenus} from "@/domain/iSysRule.ts";
import type {BreadcrumbItem, MenuIndexes} from "@/stores/types";
import type {MenuStore, MenuStoreState, MenuStoreActions} from "./types";

const initialState: MenuStoreState = {
  menus: [],
  menuMap: {},
  breadcrumbMap: {},
};

/**
 * 构建菜单索引映射
 */
const buildMenuIndexes = (menus: IMenus[]): MenuIndexes => {
  const menuMap: Record<string, IMenus> = {};
  const breadcrumbMap: Record<string, BreadcrumbItem[]> = {};

  const traverse = (items: IMenus[], parentBreadcrumb: BreadcrumbItem[] = []) => {
    for (const menu of items) {
      if (!menu.key) continue;
      menuMap[menu.key] = menu;
      const currentBreadcrumb: BreadcrumbItem[] = [
        ...parentBreadcrumb,
        { href: menu.path, title: menu.name, icon: menu.icon, local: menu.local }
      ];
      if(menu.path && !menu.link) {
        breadcrumbMap[menu.path] = currentBreadcrumb;
      }
      if (menu.children?.length) {
        traverse(menu.children, currentBreadcrumb);
      }
    }
  };
  traverse(menus);
  return { menuMap, breadcrumbMap };
};

const createAuthSlice: StateCreator<MenuStore> = (set) => ({
  ...initialState,

  menu: async () => {
    const { data } = await menu();
    const { menuMap, breadcrumbMap } = buildMenuIndexes(data.data!.menus);
    set({
      menus: data.data!.menus,
      menuMap,
      breadcrumbMap,
    });
  },

  setMenus: (menus) => set({menus}),

})

const useMenuStore = create<MenuStore>()(
  devtools(
    persist(
      createAuthSlice,
      {
        name: 'menu-storage',
        storage: createJSONStorage(() => localStorage),
      }
    ),
    { name: 'XinAdmin-Menu' }
  )
);

export type { MenuStore, MenuStoreState, MenuStoreActions};
export default useMenuStore;