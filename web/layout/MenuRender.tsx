import type {IMenus} from "@/domain/iSysRule.ts";
import IconFont from "@/components/IconFont";
import {useTranslation} from "react-i18next";
import {Menu, type MenuProps} from "antd";
import {useMemo} from "react";
import useGlobalStore from "@/stores/global";
import useMenuStore from "@/stores/menu";
import {useNavigate} from "react-router";
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

const MenuRender = () => {
  const {t} = useTranslation();
  const navigate = useNavigate();
  const menus = useMenuStore(state => state.menus);
  const layout = useGlobalStore(state => state.layout);
  const selectKey = useMenuStore(state => state.selectKey);
  const setSelectKey = useMenuStore(state => state.setSelectKey);
  const isMobile = useMobile();
  const pathMap = useMenuStore(state => state.pathMap);
  const parentKeyMap = useMenuStore(state => state.parentKeyMap);

  const menuItems: MenuItem[] = useMemo(() => {
    let menuItem: IMenus[] = menus;
    if (layout === 'mix' || layout === 'columns') {
      const rule = menus.find(item => item.key === selectKey[selectKey.length - 1]);
      menuItem = rule?.children || [];
    }
    return transformMenus(menuItem, t);
  }, [menus, layout, selectKey, t]);

  const onSelect: MenuProps['onSelect'] = (info) => {
    setSelectKey(parentKeyMap[info.key]);
    const targetPath = pathMap[info.key];
    if (!targetPath) return;
    if (targetPath.includes('http://') || targetPath.includes('https://')) {
      window.open(targetPath, '_blank');
    } else {
      navigate(targetPath);
    }
  }

  return (
    <div className={'pl-2.5 pr-2.5'}>
      <Menu
        className={"border-b-0 w-full"}
        mode={ layout === 'top' && !isMobile ? 'horizontal' : 'inline' }
        items={menuItems}
        defaultOpenKeys={selectKey}
        defaultSelectedKeys={selectKey}
        onSelect={onSelect}
      />
    </div>
  )
}

export default MenuRender;