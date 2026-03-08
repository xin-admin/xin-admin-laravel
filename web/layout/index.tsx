import AnimatedOutlet from '@/components/AnimatedOutlet';
import useGlobalStore from "@/stores/global";
import {Layout} from "antd";
import HeaderRender from "@/layout/HeaderRender";
import FooterRender from "@/layout/FooterRender";
import ColumnSiderRender from "@/layout/ColumnSiderRender";
import MenuRender from "@/layout/MenuRender";
import MobileDrawerMenu from "@/layout/MobileDrawerMenu";
import SettingDrawer from "@/layout/SettingDrawer";
import PageTitle from "@/components/PageTitle";
import useMenuStore from "@/stores/menu";
import {useEffect} from "react";
import {useLocation} from "react-router";
import useMobile from "@/hooks/useMobile";

const {Content, Sider} = Layout;

const LayoutRender = () => {
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const location = useLocation();
  const layout = useGlobalStore(state => state.layout);
  const collapsed = useGlobalStore(state => state.collapsed);
  const isMobile = useMobile();
  const setSelectKey = useMenuStore(state => state.setSelectKey);
  const routeMap = useMenuStore(data => data.routeMap);
  const logo = useGlobalStore(state => state.logo);
  const title = useGlobalStore(state => state.title);
  const pathMap = useMenuStore(state => state.pathMap);
  const parentKeyMap = useMenuStore(state => state.parentKeyMap);

  useEffect(() => {
    const pathname = location.pathname;
    const keys = Object.keys(pathMap).find(item => pathMap[item] === pathname);
    if(!keys) {
      return;
    }
    setSelectKey(parentKeyMap[keys]);
  }, [location, setSelectKey, routeMap]);

  return (
    <Layout hasSider className="min-h-screen" style={{ background: themeConfig.background }}>
      <PageTitle>
        {/* 主题设置抽屉 */}
        <SettingDrawer />

        { isMobile ? (
          <MobileDrawerMenu />
        ) : (
          <>
            {layout === 'columns' && <ColumnSiderRender/> }
            {(layout === "mix" || layout === "side") && (
              <Sider
                collapsed={collapsed}
                width={themeConfig.siderWeight}
                style={{borderRight: "1px solid " + themeConfig.colorBorder}}
                className={"sticky top-0 backdrop-blur-xs [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden bottom-0 overflow-auto h-screen"}
              >
                <div className="flex items-center h-14 justify-center">
                  <img className={"w-9"} src={logo} alt="logo"/>
                  { !collapsed && <span className={"font-semibold text-[20px] ml-5"}>{title}</span> }
                </div>
                <MenuRender />
              </Sider>
            )}
          </>
        )}
        <Layout className={"relative"}>
          <HeaderRender/>
          <Content style={{padding: themeConfig.bodyPadding}}>
            <AnimatedOutlet/>
          </Content>
          <FooterRender/>
        </Layout>
      </PageTitle>
    </Layout>
  );
};

export default LayoutRender;
