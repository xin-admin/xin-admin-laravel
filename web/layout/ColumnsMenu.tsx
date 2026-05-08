import IconFont from "@/components/IconFont";
import MenuRender from "@/layout/MenuRender.tsx";
import {useLayoutContext} from "@/layout/LayoutContext.tsx";
import {useTranslation} from "react-i18next";
import {theme} from "antd";
import useGlobalStore from "@/stores/global";
import React from "react";
import type {IMenus} from "@/domain/iSysRule.ts";
import {useNavigate} from "react-router";

const {useToken} = theme;

const ColumnsMenu: React.FC = () => {
  const {t} = useTranslation();
  const {token} = useToken();
  const navigate = useNavigate();
  const {menus, parentKey, collapsed, setParentKey, setSelectKey} = useLayoutContext();
  const themeConfig = useGlobalStore(state => state.themeConfig);

  const onMenuChange = (menu: IMenus) => {
    if(!menu.key) return;
    setParentKey(menu.key);
    if(menu.type === 'menu') {
      return;
    }
    setSelectKey([menu.key]);
    // 外链路由
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
    <div className={'flex h-full overflow-auto'}>
      <div
        className={'box-border shrink-0'}
        style={{
          borderRight: !collapsed ? "1px solid " + themeConfig.colorBorder : 'none',
          width: 79
        }}
      >
        {/* 侧栏一级菜单 */}
        {menus.filter(item => item.hidden).map(rule => (
          <div
            key={rule.key}
            style={{
              backgroundColor: parentKey === rule.key ? token.colorPrimaryBg : 'transparent',
              color: parentKey === rule.key ? token.colorPrimary : themeConfig.siderColor,
            }}
            className={"flex items-center justify-center flex-col p-2 mb-2 pt-3 pb-3 cursor-pointer"}
            onClick={() => onMenuChange(rule)}
          >
            <IconFont name={rule.icon} style={{ fontSize: 18 }}/>
            <span className={"mt-1 truncate w-full text-center"}>{rule.local ? t(rule.local) : rule.name}</span>
          </div>
        ))}
      </div>
      { !collapsed && (
        <div className={'flex-1'}>
          <MenuRender />
        </div>
      )}
    </div>
  )
}

export default ColumnsMenu;
