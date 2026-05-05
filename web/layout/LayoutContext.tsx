import React, {createContext, useCallback, useContext, useEffect, useMemo, useState} from "react";
import {useLocation, useNavigate} from "react-router";
import {menu} from "@/api/system/sys_user";
import useGlobalStore from "@/stores/global";
import type {IMenus} from "@/domain/iSysRule.ts";
import {findMenuByKey, getMenuParentKeys} from "@/layout/utils.ts";

interface LayoutContextType {
  menus: IMenus[];
  parentKey: string | undefined;
  selectKey: string[];
  siderWidth: number;
  collapsed: boolean;
  onMenuChange: (key: string, isSub?: boolean) => void;
  setCollapsed: (collapsed: boolean) => void;
}

const LayoutContext = createContext<LayoutContextType | null>(null);

export const LayoutProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const navigate = useNavigate();
  const location = useLocation();
  const layout = useGlobalStore(state => state.layout);
  const themeConfig = useGlobalStore(state => state.themeConfig);

  const [menus, setMenus] = useState<IMenus[]>([]);

  const [parentKey, setParentKey] = useState<string>();
  const [selectKey, setSelectKey] = useState<string[]>([]);
  const [showSubMenu, setShowSubMenu] = useState(true);

  const [collapsed, setCollapsed] = useState(false);

  // 获取菜单数据
  useEffect(() => {
    menu().then(({ data }) => {
      const items = data.data!.menus;
      setMenus(items);
    });
  }, []);

  // 菜单点击
  const onMenuChange = useCallback((key: string, isSub: boolean = true) => {
    const menu = findMenuByKey(menus, key);
    if(!menu || !menu.key) return;
    if (menu.type === 'route') {
      if (menu.link) {
        window.open(menu.path, '_blank');
        return;
      }
      if(!menu.path) {
        return;
      }
      setSelectKey([menu.key]);
      navigate(menu.path);
      if(!isSub) {
        setShowSubMenu(false);
        setParentKey(key);
      }
      return;
    }
    setShowSubMenu(true);
    setParentKey(key);
  }, [navigate, layout, menus]);

  // 根据 URL 恢复菜单状态
  useEffect(() => {
    const pathname = location.pathname;
    const ancestors = getMenuParentKeys(menus, pathname);
    if(ancestors && ancestors.length) {
      setSelectKey(ancestors);
      setParentKey(ancestors[0]);
    }
  }, [menus]);

  const siderWidth = useMemo(() => {
    const menuWidth = themeConfig.siderWeight ? themeConfig.siderWeight : 226;
    const columnWidth = layout === 'columns' ? 79 : 0;
    return columnWidth + (showSubMenu ? menuWidth : 0);
  }, [themeConfig, layout, showSubMenu]);

  return (
    <LayoutContext.Provider value={{
      menus,
      parentKey,
      selectKey,
      siderWidth,
      collapsed,
      onMenuChange,
      setCollapsed,
    }}>
      {children}
    </LayoutContext.Provider>
  );
};

export const useLayoutContext = () => {
  const ctx = useContext(LayoutContext);
  if (!ctx) {
    throw new Error('useLayoutContext must be used within LayoutProvider');
  }
  return ctx;
};
