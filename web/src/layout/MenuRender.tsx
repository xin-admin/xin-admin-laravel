import type {IMenus} from "@/domain/iSysRule.ts";
import {useTranslation} from "react-i18next";
import {Menu, type MenuProps} from "antd";
import React, {useEffect, useState} from "react";
import useGlobalStore from "@/stores/global";
import {useLayoutContext} from "@/layout/LayoutContext";
import useMobile from "@/hooks/useMobile.ts";
import IconFont from "@/components/IconFont";
import {findMenuByKey} from "@/layout/utils.ts";
import {useNavigate} from "react-router";

type MenuItem = Required<MenuProps>['items'][number];

/**
 * 构建菜单Item
 * @param nodes
 * @param t
 */
const transformMenus = (nodes: IMenus[], t: any): MenuItem[] => {
  return nodes.reduce<MenuItem[]>((acc, node) => {
    if (!['route', 'menu'].includes(node.type!) || !node.hidden) {
      return acc;
    }
    const menuItem: MenuItem = {
      label: node.local ? t(node.local) : node.name,
      icon: node.icon ? <IconFont name={node.icon} /> : undefined,
      key: node.key!,
    };
    if (node.type === 'menu' && node.children?.length) {
      (menuItem as any).children = transformMenus(node.children, t);
    }
    acc.push(menuItem);
    return acc;
  }, []);
};

const MenuRender: React.FC = () => {
  const {t} = useTranslation();
  const isMobile = useMobile();
  const navigate = useNavigate();
  const [menuItems, setMenuItems] = useState<MenuItem[]>([]);
  const layout = useGlobalStore(state => state.layout);
  const { menus, parentKey, selectKey, setSelectKey, setParentKey } = useLayoutContext();

  useEffect(() => {
    if (isMobile) {
      setMenuItems(transformMenus(menus, t));
      return;
    }
    if (['columns', 'mix'].includes(layout) && parentKey) {
      const rule = menus.find(item => item.key === parentKey);
      setMenuItems(transformMenus(rule?.children || [], t));
    } else {
      setMenuItems(transformMenus(menus, t));
    }
  }, [menus, layout, parentKey, t, isMobile]);

  const onMenuChange = (keyPath: string[]) => {
    setSelectKey(keyPath);
    if (['top', 'side'].includes(layout)) {
      setParentKey(keyPath[keyPath.length -1 ]);
    }
    const menu = findMenuByKey(menus, keyPath[0]);
    if (!menu || !menu.key) return;
    if(menu.type === 'route' && menu.link) {
      window.open(menu.path, '_blank');
      return;
    }
    if(menu.type === 'route' && menu.path) {
      navigate(menu.path);
      return;
    }
  }

  return (
    <>
      { menuItems.length > 0 && (
        <Menu
          className={"border-b-0 flex-1"}
          mode={ layout === 'top' && !isMobile ? 'horizontal' : 'inline' }
          items={menuItems}
          onSelect={info => onMenuChange(info.keyPath)}
          selectedKeys={selectKey}
        />
      ) }
    </>
  )
}

export default MenuRender;
