import React, {createContext, useContext, useEffect, useState} from "react";
import {useLocation} from "react-router";
import {menu} from "@/api/system/sys_user";
import type {IMenus} from "@/domain/iSysRule.ts";
import {getMenuParentKeys} from "@/layout/utils.ts";

interface LayoutContextType {
  menus: IMenus[];
  parentKey: string | undefined;
  selectKey: string[];
  collapsed: boolean;
  setCollapsed: (collapsed: boolean) => void;
  setParentKey: (key: string) => void;
  setSelectKey: (keys: string[]) => void;
}

const LayoutContext = createContext<LayoutContextType | null>(null);

export const LayoutProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const location = useLocation();
  const [menus, setMenus] = useState<IMenus[]>([]);
  const [parentKey, setParentKey] = useState<string>();
  const [selectKey, setSelectKey] = useState<string[]>([]);

  const [collapsed, setCollapsed] = useState(false);

  // 获取菜单数据
  useEffect(() => {
    menu().then(({ data }) => {
      const items = data.data!.menus;
      setMenus(items);
    });
  }, []);

  // 根据 URL 恢复菜单状态
  useEffect(() => {
    const pathname = location.pathname;
    const ancestors = getMenuParentKeys(menus, pathname);
    if(ancestors && ancestors.length) {
      setSelectKey(ancestors);
      setParentKey(ancestors[0]);
    }
  }, [menus]);

  return (
    <LayoutContext.Provider value={{
      menus,
      parentKey,
      selectKey,
      collapsed,
      setParentKey,
      setCollapsed,
      setSelectKey,
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
