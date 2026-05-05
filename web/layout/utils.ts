import type {IMenus} from "../domain/iSysRule";

export interface BreadcrumbItem {
  href?: string;
  title?: string;
  icon?: string;
  local?: string;
}

/**
 * 构建面包屑Map
 * @param menus
 */
export function buildBreadcrumbMap(menus: IMenus[]) {
  const breadcrumbMap: Record<string, BreadcrumbItem[]> = {};
  const processMenu = (item: IMenus, breadcrumbs: BreadcrumbItem[] = []) => {
    if (!item.key) return;
    const currentBreadcrumb: BreadcrumbItem = {
      href: item.path,
      title: item.name,
      icon: item.icon,
      local: item.local,
    };
    const newBreadcrumbs = [...breadcrumbs, currentBreadcrumb];
    breadcrumbMap[item.key] = newBreadcrumbs;
    if (item.children && item.children.length) {
      item.children.forEach(child => processMenu(child, newBreadcrumbs));
    }
  };
  menus.forEach(item => processMenu(item));
  return breadcrumbMap;
}

/**
 * 通过路径查找菜单项
 * @param menus
 * @param path
 */
export function findMenuByPath(menus: IMenus[], path: string): IMenus | null {
  for (const menu of menus) {
    if (menu.path === path) {
      return menu;
    }
    if (menu.children && menu.children.length > 0) {
      const result = findMenuByPath(menu.children, path);
      if (result) {
        return result;
      }
    }
  }
  return null;
}

/**
 * 通过路径构建父菜单 keys
 * @param menus
 * @param path
 * @param parentKeys
 */
export function getMenuParentKeys(menus: IMenus[], path: string, parentKeys: string[] = []): string[] | null {
  for (const menu of menus) {
    if (!menu.key) continue;
    const keys = [...parentKeys, menu.key];
    if (menu.path === path) {
      return keys;
    }
    if (menu.children && menu.children.length > 0) {
      const result = getMenuParentKeys(menu.children, path, keys);
      if (result) {
        return result;
      }
    }
  }
  return null;
}

/**
 * 通过路径查找菜单项
 * @param menus
 * @param key
 */
export function findMenuByKey(menus: IMenus[], key: string): IMenus | null {
  for (const menu of menus) {
    if (menu.key === key) {
      return menu;
    }
    if (menu.children && menu.children.length > 0) {
      const result = findMenuByKey(menu.children, key);
      if (result) {
        return result;
      }
    }
  }
  return null;
}
