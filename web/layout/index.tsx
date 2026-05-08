import useGlobalStore from "@/stores/global";
import {Layout} from "antd";
import HeaderRender from "@/layout/HeaderRender";
import FooterRender from "@/layout/FooterRender";
import MenuRender from "@/layout/MenuRender";
import MobileDrawerMenu from "@/layout/MobileDrawerMenu";
import SettingDrawer from "@/layout/SettingDrawer";
import PageTitle from "@/components/PageTitle";
import {LayoutProvider, useLayoutContext} from "@/layout/LayoutContext";
import {Outlet} from "react-router";
import useMobile from "@/hooks/useMobile.ts";
import ColumnsMenu from "@/layout/ColumnsMenu.tsx";
import React, {useEffect, useState} from "react";

const {Content, Sider} = Layout;

const LayoutContent: React.FC = () => {
  const isMobile = useMobile();
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const layout = useGlobalStore(state => state.layout);
  const [siderWidth, setSiderWidth] = useState<number>(226);
  const {menus, collapsed, parentKey} = useLayoutContext();

  useEffect(() => {
    const menuWidth = themeConfig.siderWeight ? themeConfig.siderWeight : 226;
    const columnWidth = layout === 'columns' ? 79 : 0;
    const rule = menus.find(item => item.key === parentKey);
    if(['mix', 'columns'].includes(layout)) {
      if(rule?.children && rule?.children.length) {
        setSiderWidth(columnWidth + menuWidth)
      } else {
        setSiderWidth(columnWidth);
      }
    } else {
      setSiderWidth(menuWidth);
    }
  }, [themeConfig, layout, menus, parentKey]);

  return (
    <Layout className="min-h-screen" style={{ background: themeConfig.background }}>
      {/* 页面标题 */}
      <PageTitle />
      {/* 主题设置抽屉 */}
      <SettingDrawer />
      {/* 顶栏 */}
      <HeaderRender />

      <Layout hasSider className={"relative"}>
        { isMobile && <MobileDrawerMenu /> }
        { layout !== "top" && !isMobile && (
          <Sider
            collapsed={collapsed}
            width={siderWidth}
            style={{
              borderRight: "1px solid " + themeConfig.colorBorder,
              height: `calc(100vh - ${themeConfig.headerHeight}px)`,
              top: themeConfig.headerHeight,
              overflowY: "auto",
              position: 'sticky'
            }}
          >
            { layout === "columns" ? <ColumnsMenu/> : <MenuRender />}
          </Sider>
        )}
        <Layout>
          <Content style={{padding: themeConfig.bodyPadding}}>
            <Outlet/>
          </Content>
          <FooterRender/>
        </Layout>
      </Layout>
    </Layout>
  );
};

const LayoutRender = () => (
  <LayoutProvider>
    <LayoutContent />
  </LayoutProvider>
);

export default LayoutRender;
