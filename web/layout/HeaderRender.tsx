import React, {useEffect, useState} from "react";
import {Button, ConfigProvider, Layout, Menu, type MenuProps, type ThemeConfig} from "antd";
import useGlobalStore from "@/stores/global";
import useMenuStore from "@/stores/menu";
import HeaderRightRender from "@/layout/HeaderRightRender";
import IconFont from "@/components/IconFont";
import {useTranslation} from "react-i18next";
import MenuRender from "@/layout/MenuRender.tsx";
import {useNavigate} from "react-router";
import {MenuFoldOutlined, MenuUnfoldOutlined} from "@ant-design/icons";
import BreadcrumbRender from "@/layout/BreadcrumbRender.tsx";
import useMobile from "@/hooks/useMobile";

const {Header} = Layout;

const HeaderRender: React.FC = () => {
  const {t} = useTranslation();
  const navigate = useNavigate();
  const isMobile = useMobile();
  const logo = useGlobalStore(state => state.logo);
  const title = useGlobalStore(state => state.title);
  const menus = useMenuStore(state => state.menus);
  const layout = useGlobalStore(state => state.layout);
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const setSelectKey = useMenuStore(state => state.setSelectKey);
  const selectKey = useMenuStore(state => state.selectKey);
  const routeMap = useMenuStore(state => state.routeMap);
  const collapsed = useGlobalStore(state => state.collapsed);
  const setCollapsed = useGlobalStore(state => state.setCollapsed);
  const theme: ThemeConfig = {
    cssVar: true,
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

  const onSelect: MenuProps['onSelect'] = (info: any) => {
    setSelectKey([info.key]);
    const route = routeMap[info.key];
    if(route && route.type === 'route' && route.path) {
      if (info.key.includes('http://') || info.key.includes('https://')) {
        window.open(info.key, '_blank');
      } else {
        navigate(route.path);
      }
    }
  }

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
            {/* 顶栏标题 */}
            {layout === 'top' && (
              <>
                <img className={"w-9"} src={logo} alt="logo"/>
                <span className={"font-semibold text-[20px] ml-5"}>{title}</span>
              </>
            )}
            {/* 侧边栏开关 */}
            {['mix', 'side'].includes(layout) && (
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
                onSelect={onSelect}
                selectedKeys={selectKey}
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