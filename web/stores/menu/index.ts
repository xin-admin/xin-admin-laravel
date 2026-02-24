import {create, type StateCreator} from 'zustand';
import {createJSONStorage, devtools, persist} from "zustand/middleware";
import {menu} from "@/api/system/sys_user";
import type {IMenus} from "@/domain/iSysRule.ts";
import type {MenuStore, MenuStoreState, MenuStoreActions, BreadcrumbItem} from "./types";

const initialState: MenuStoreState = {
  menus: [],
  selectKey: [],
  routeMap: {},
  pathMap: {},
  breadcrumbMap: {},
  parentKeyMap: {}
};

/**
 * 将菜单树转换为路径映射表和 key->path 映射表
 * @param menus 菜单数组
 * @returns 路径映射表和 key->path 映射表
 */
export function buildRouteMap(menus: IMenus[]): {
  routeMap: MenuStoreState['routeMap'];
  pathMap: MenuStoreState['pathMap'];
  breadcrumbMap: MenuStoreState['breadcrumbMap'];
  parentKeyMap: MenuStoreState['parentKeyMap'];
} {
  const routeMap: MenuStoreState['routeMap'] = {};
  const pathMap: MenuStoreState['pathMap'] = {};
  const parentKeyMap: MenuStoreState['parentKeyMap'] = {};
  const breadcrumbMap: MenuStoreState['breadcrumbMap'] = {};

  const processMenu = (menu: IMenus, breadcrumbs: BreadcrumbItem[], parentKey: string[]) => {
    if(!menu.key) return;
    routeMap[menu.key] = menu;
    const currentBreadcrumb: BreadcrumbItem = {
      href: menu.path,
      title: menu.name,
      icon: menu.icon,
      local: menu.local,
    };
    const newBreadcrumbs = [...breadcrumbs, currentBreadcrumb];
    breadcrumbMap[menu.key] = newBreadcrumbs;
    const newParentKey = [menu.key, ...parentKey];
    parentKeyMap[menu.key] = newParentKey;
    if (menu.path) {
      pathMap[menu.key] = menu.path;
    }

    if (menu.children && menu.children.length > 0) {
      menu.children.forEach(child => {
        processMenu(child, newBreadcrumbs, newParentKey);
      });
    }
  };

  menus.forEach(menu => {
    processMenu(menu, [], []);
  });

  return { routeMap, pathMap, breadcrumbMap, parentKeyMap };
}

const createAuthSlice: StateCreator<MenuStore> = (set) => ({
  ...initialState,
  menu: async () => {
    const { data } = await menu();
    const { routeMap, pathMap, breadcrumbMap, parentKeyMap } = buildRouteMap(data.data!.menus);
    set({
      menus: data.data!.menus,
      routeMap,
      pathMap,
      parentKeyMap,
      breadcrumbMap
    });
    return data.data!.menus;
  },
  setMenus: (menus) => set({menus}),
  setSelectKey: (selectKey) => set({selectKey}),
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

export type { MenuStore, MenuStoreState, MenuStoreActions, BreadcrumbItem};
export default useMenuStore;
