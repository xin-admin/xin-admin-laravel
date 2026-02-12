import {ConfigProvider, Layout, theme} from "antd";
import useGlobalStore from "@/stores/global";
import useMenuStore from "@/stores/menu";
import React, {useEffect, useMemo, useState} from "react";
import IconFont from "@/components/IconFont";
import {useNavigate} from "react-router";
import {useTranslation} from "react-i18next";
import MenuRender from "@/layout/MenuRender.tsx";
import type {ISysRule} from "@/domain/iSysRule.ts";

const {Sider} = Layout;
const {useToken} = theme;

const ColumnSiderRender: React.FC = () => {
  const navigate = useNavigate();
  const {t} = useTranslation();
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const logo = useGlobalStore(state => state.logo);
  const title = useGlobalStore(state => state.title);
  const setSelectKey = useMenuStore(state => state.setSelectKey);
  const selectKey = useMenuStore(state => state.selectKey);
  const menus = useMenuStore(state => state.menus);
  const {token} = useToken();
  const [showSubMenu, setShowSubMenu] = useState(true);

  useEffect(() => {
    if(selectKey.length <= 1) {
      setShowSubMenu(false);
    }
  }, []);

  const menuClick = (rule: ISysRule) => {
    setSelectKey([rule.key!]);
    if (rule.type === 'route') {
      if (rule.link) {
        window.open(rule.path, '_blank')
      } else {
        navigate(rule.path!);
        setShowSubMenu(false);
      }
    } else {
      setShowSubMenu(true);
    }
  }

  const siderWidth = useMemo(() => {
    const menuWidth = themeConfig.siderWeight ? themeConfig.siderWeight : 226;
    return 72 + (showSubMenu ? menuWidth : 0);
  }, [themeConfig.siderWeight, showSubMenu]);

  return (
    <ConfigProvider
      theme={{
        token: { colorTextBase: themeConfig.siderColor },
        cssVar: true
      }}
    >
      <Sider
        width={siderWidth}
        className={"h-screen sticky top-0 bottom-0"}
        style={{color: themeConfig.siderColor}}
      >
        <div className={"w-full flex h-full"}>
          <div
            className={'w-18 box-border h-full overflow-auto [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden'}
            style={{borderRight: "1px solid " + themeConfig.colorBorder}}
          >
            <div className={"w-full flex items-center justify-center pt-2.5 pb-2.5"}>
              <img className={"w-9"} src={logo} alt="logo"/>
            </div>
            {/* 侧栏菜单 */}
            {menus.filter(item => item.hidden).map(rule => (
              <div
                key={rule.key}
                style={{
                  backgroundColor: selectKey.at(-1) === rule.key ? token.colorPrimaryBg : 'transparent',
                  color: selectKey.at(-1) === rule.key ? token.colorPrimary : themeConfig.siderColor,
                }}
                className={"flex items-center justify-center flex-col p-2 mb-2 pt-3 pb-3 cursor-pointer"}
                onClick={() => menuClick(rule)}
              >
                <IconFont name={rule.icon}/>
                <span className={"mt-1 truncate w-full text-center"}>{rule.local ? t(rule.local) : rule.name}</span>
              </div>
            ))}
          </div>

          {showSubMenu && (
            <div
              style={{
                width: themeConfig.siderWeight,
                borderRight: "1px solid " + themeConfig.colorBorder
              }}
            >
              <div className={"font-semibold text-[20px] text-center pt-2.5 pb-2.5"}>
                {title}
              </div>
              <MenuRender/>
            </div>
          )}
        </div>
      </Sider>
    </ConfigProvider>
  )
}

export default ColumnSiderRender