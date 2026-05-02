import type {IMenus, ISysRule} from "@/domain/iSysRule.ts";
import IconFont from "@/components/IconFont";
import {useTranslation} from "react-i18next";
import {Menu, type MenuProps} from "antd";
import React, {useMemo} from "react";
import useGlobalStore from "@/stores/global";
import useMenuStore from "@/stores/menu";
import useMobile from "@/hooks/useMobile";
type MenuItem = Required<MenuProps>['items'][number];

const transformMenus = (nodes: IMenus[], t: any): MenuItem[] => {
  return nodes.reduce<MenuItem[]>((acc, node) => {
    if (!['route', 'menu'].includes(node.type!) || !node.hidden) {
      return acc;
    }
    const menuItem: MenuItem = {
      label: node.local ? t(node.local) : node.name,
      icon: node.icon ? <IconFont name={node.icon}/> : undefined,
      key: node.key!,
    };
    if (node.type === 'menu' && node.children?.length) {
      (menuItem as any).children = transformMenus(node.children, t);
    }
    acc.push(menuItem);
    return acc;
  }, []);
};

interface HeaderProps {
  onMenuChange: (rule: ISysRule) => void;
  parentKey?: string;
}

const MenuRender: React.FC<HeaderProps> = ({onMenuChange, parentKey}) => {
  const {t} = useTranslation();
  const menus = useMenuStore(state => state.menus);
  const layout = useGlobalStore(state => state.layout);
  const isMobile = useMobile();
  const routeMap = useMenuStore(state => state.routeMap);

  const menuItems: MenuItem[] = useMemo(() => {
    let menuItem: IMenus[] = menus;
    if (parentKey) {
      const rule = menus.find(item => item.key === parentKey);
      menuItem = rule?.children || [];
    }
    return transformMenus(menuItem, t);
  }, [menus, layout, parentKey, t]);

  const onSelect: MenuProps['onSelect'] = (info) => {
    const route = routeMap[info.key];
    if (route) onMenuChange(route);
  }

  return (
    <div className={'pl-2.5 pr-2.5'}>
      <Menu
        className={"border-b-0 w-full"}
        mode={ layout === 'top' && !isMobile ? 'horizontal' : 'inline' }
        items={menuItems}
        onSelect={onSelect}
      />
    </div>
  )
}

export default MenuRender;
