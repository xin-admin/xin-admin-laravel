import React, {useEffect, useState} from "react";
import {Button, ConfigProvider, Layout, Menu, type MenuProps, type ThemeConfig} from "antd";
import useGlobalStore from "@/stores/global";
import HeaderRightRender from "@/layout/HeaderRightRender";
import IconFont from "@/components/IconFont";
import {useTranslation} from "react-i18next";
import MenuRender from "@/layout/MenuRender.tsx";
import {MenuFoldOutlined, MenuUnfoldOutlined} from "@ant-design/icons";
import BreadcrumbRender from "@/layout/BreadcrumbRender.tsx";
import {useLayoutContext} from "@/layout/LayoutContext";
import useMobile from "@/hooks/useMobile";

const {Header} = Layout;

const HeaderRender: React.FC = () => {
  const {t} = useTranslation();
  const isMobile = useMobile();
  const logo = useGlobalStore(state => state.logo);
  const title = useGlobalStore(state => state.title);
  const layout = useGlobalStore(state => state.layout);
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const { menus, onMenuChange, parentKey, collapsed, setCollapsed } = useLayoutContext();

  const theme: ThemeConfig = {
    token: { colorTextBase: themeConfig.headerColor },
    components: {
      Menu: {
        activeBarBorderWidth: 0,
        itemBg: 'transparent',
      }
    }
  }
  const [mixMenu, setMixMenu] = useState<MenuProps['items']>([]);

  useEffect(() => {
    setMixMenu(menus.filter(item => item.hidden).map(item => ({
      label: item.local ? t(item.local) : item.name,
      icon: item.icon ? <IconFont name={item.icon}/> : false,
      key: item.key!,
    })));
  }, [menus, t]);

  return (
    <ConfigProvider theme={theme}>
      { isMobile ? (
        <Header className={"flex sticky z-1 top-0 backdrop-blur-xs justify-between items-center"}>
          <div className={"flex items-center"}>
            <img className={"w-9 mr-5"} src={logo} alt="logo"/>
            <span className={"font-semibold text-[20px] mr-2"}>{title}</span>
          </div>
          <HeaderRightRender/>
        </Header>
      ) : (
        <Header
          className={"flex sticky z-1 top-0 backdrop-blur-xs"}
          style={{borderBottom: "1px solid " + themeConfig.colorBorder}}
        >
          <div className={"flex items-center relative"}>
            <img className={"h-8"} src={logo} alt="logo"/>
            <span className={"font-semibold text-[20px] ml-5 mr-5"}>{title}</span>
            {/* 侧边栏开关 */}
            {layout !== 'top' && (
              <Button
                type={'text'}
                className={'text-[16px] mr-2.5'}
                onClick={() => setCollapsed(!collapsed)}
              >
                { collapsed ? <MenuUnfoldOutlined/> : <MenuFoldOutlined/> }
              </Button>
            )}
            {/* 面包屑 */}
            {['columns', 'side'].includes(layout) && <BreadcrumbRender/> }
          </div>
          <div className={"overflow-hidden flex-1"}>
            {/* 顶部菜单 */}
            { layout == 'top' && <MenuRender /> }
            {/* 混合布局模式下的顶部菜单 */}
            { layout == 'mix' && (
              <Menu
                className={"border-b-0 w-full"}
                mode="horizontal"
                items={mixMenu}
                onSelect={(info) => onMenuChange(info.key, false)}
                selectedKeys={[parentKey!]}
              />
            )}
          </div>
          <HeaderRightRender/>
        </Header>
      )}
    </ConfigProvider>
  )
}

export default HeaderRender;
