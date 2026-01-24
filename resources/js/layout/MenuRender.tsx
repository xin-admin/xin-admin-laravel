import type {IMenus} from "@/domain/iSysRule.ts";
import IconFont from "@/components/IconFont";
import {useTranslation} from "react-i18next";
import {Card, Layout, Menu, type MenuProps} from "antd";
import {useCallback, useMemo} from "react";
import { useGlobalStore } from "@/stores";
import useMenuStore from "@/stores/menu";
import {useNavigate} from "react-router";
type MenuItem = Required<MenuProps>['items'][number];
const {Sider} = Layout;
// 菜单项转换
const transformMenus = (nodes: IMenus[], t: any): MenuItem[] => {
  return nodes.reduce<MenuItem[]>((acc, node) => {
    if (!['route', 'menu'].includes(node.type!) || !node.hidden) {
      return acc;
    }

    const menuItem: MenuItem = {
      label: node.local ? t(node.local) : node.name,
      icon: node.icon ? <IconFont name={node.icon} style={{fontSize: 20}}/> : undefined,
      key: node.key!,
    };

    // 仅当有子菜单时才递归处理
    if (node.type === 'menu' && node.children?.length) {
      (menuItem as any).children = transformMenus(node.children, t);
    }

    acc.push(menuItem);
    return acc;
  }, []);
};

const MenuRender = () => {
  const {t} = useTranslation();
  const logo= useGlobalStore(state => state.logo);
  const title = useGlobalStore(state => state.title);
  const menus = useMenuStore(state => state.menus);
  const menuMap = useMenuStore(state => state.menuMap);
  const collapsed = useGlobalStore(state => state.collapsed);
  const layout = useGlobalStore(state => state.layout);
  const menuParentKey = useGlobalStore(state => state.menuParentKey);
  const isMobile = useGlobalStore(state => state.isMobile);
  const navigate = useNavigate();
  // 使用 useMemo 缓存菜单数据源
  const menuSource = useMemo(() => {
    if (layout === 'mix' || layout === 'columns') {
      const rule = menus.find(item => item.key === menuParentKey);
      return rule?.children || [];
    }
    return menus;
  }, [menus, layout, menuParentKey]);

  // 使用 useMemo 缓存转换后的菜单项
  const menuItems = useMemo(() => {
    return transformMenus(menuSource, t);
  }, [menuSource, t]);

  const menuClick: MenuProps['onClick'] = useCallback((info: any) => {
    const menu = menuMap[info.key];
    if(! menu.path) return;
    if (menu.link) {
      window.open(menu.path, '_blank');
    } else {
      navigate(menu.path);
    }
  }, [menuMap, t, navigate]);

  return (
    <Sider
      collapsed={collapsed}
      width={260}
      className={"top-0 overflow-auto h-dvh sticky p-2.5"}
    >
      <Card variant={'borderless'} size={'small'} className={'top-0 h-full'} styles={{body: {height: '100%'}}}>
        <div className={'h-full flex flex-col'}>
          <div className={"flex items-center p-2.5 flex-shrink-0"}>
            <img className={"w-8"} src={logo} alt="logo"/>
            <div className={`transition-all duration-1000 ease-in-out ${collapsed ? "max-h-0 opacity-0 overflow-hidden" : "max-h-[100px] opacity-100"}`}>
              <span className="font-semibold text-[20px] pl-5">{title}</span>
            </div>
          </div>
          <div className={"flex-1 overflow-auto [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"}>
            <Menu
              inlineIndent={10}
              mode={ layout === 'top' && !isMobile ? 'horizontal' : 'inline' }
              items={menuItems}
              onClick={menuClick}
            />
          </div>
          <div className={'flex-shrink-0 h-10'}>

          </div>
        </div>
      </Card>
    </Sider>
  )
}

export default MenuRender;