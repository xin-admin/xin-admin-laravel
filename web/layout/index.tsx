import AnimatedOutlet from '@/components/AnimatedOutlet';
import useGlobalStore from "@/stores/global";
import {Layout, theme} from "antd";
import HeaderRender from "@/layout/HeaderRender";
import FooterRender from "@/layout/FooterRender";
import MenuRender from "@/layout/MenuRender";
import MobileDrawerMenu from "@/layout/MobileDrawerMenu";
import SettingDrawer from "@/layout/SettingDrawer";
import PageTitle from "@/components/PageTitle";
import useMenuStore from "@/stores/menu";
import {useState} from "react";
import {useNavigate} from "react-router";
import useMobile from "@/hooks/useMobile";
import type {ISysRule} from "@/domain/iSysRule.ts";
import IconFont from "@/components/IconFont";
import {useTranslation} from "react-i18next";

const {useToken} = theme;
const {Content, Sider} = Layout;

const LayoutRender = () => {
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const navigate = useNavigate();
  const {t} = useTranslation();
  const layout = useGlobalStore(state => state.layout);
  const collapsed = useGlobalStore(state => state.collapsed);
  const isMobile = useMobile();
  const [showSubMenu, setShowSubMenu] = useState(true);
  const menus = useMenuStore(state => state.menus);
  const {token} = useToken();

  const [parentKey, setParentKey] = useState<string>();

  const siderWidth = () => {
    const menuWidth = themeConfig.siderWeight ? themeConfig.siderWeight : 226;
    const columnWidth = layout === 'columns' ? 72 : 0;
    return columnWidth + (showSubMenu ? menuWidth : 0);
  };

  const onMenuChange = (rule: ISysRule) => {
    if (rule.type === 'route') {
      if (rule.link) {
        window.open(rule.path, '_blank')
      } else {
        navigate(rule.path!);
        setShowSubMenu(false);
      }
    } else {
      setParentKey(rule.key);
      setShowSubMenu(true);
    }
  }

  return (
    <Layout className="min-h-screen" style={{ background: themeConfig.background }}>
      {/* 页面标题 */}
      <PageTitle />
      {/* 主题设置抽屉 */}
      <SettingDrawer />
      {/* 顶栏 */}
      <HeaderRender onMenuChange={onMenuChange}/>

      <Layout hasSider className={"relative"}>
        { isMobile && <MobileDrawerMenu /> }
        { layout !== "top" && !isMobile && (
          <Sider
            collapsed={collapsed}
            width={siderWidth()}
            style={{
              borderRight: "1px solid " + themeConfig.colorBorder,
              height: `calc(100vh - ${themeConfig.headerHeight}px)`,
              top: themeConfig.headerHeight,
              overflowY: "auto",
              position: 'sticky'
            }}
          >
            { layout === "columns" && (
              <div
                className={'w-18 box-border h-full overflow-auto'}
                style={{borderRight: "1px solid " + themeConfig.colorBorder}}
              >
                {/* 侧栏菜单 */}
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
                    <IconFont name={rule.icon}/>
                    <span className={"mt-1 truncate w-full text-center"}>{rule.local ? t(rule.local) : rule.name}</span>
                  </div>
                ))}
              </div>
            )}
            <MenuRender onMenuChange={onMenuChange} parentKey={parentKey}/>
          </Sider>
        )}
        <Layout>
          <Content style={{padding: themeConfig.bodyPadding}}>
            <AnimatedOutlet/>
          </Content>
          <FooterRender/>
        </Layout>
      </Layout>
    </Layout>
  );
};

export default LayoutRender;
