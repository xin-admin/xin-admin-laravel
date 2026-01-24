import { useEffect } from "react";
import { useGlobalStore } from "@/stores";
import {useLocation} from "react-router";
import {useTranslation} from "react-i18next";
import useMenuStore from "@/stores/menu";

/**
 * 页面标题组件
 */
const PageTitle = () => {
  const {t} = useTranslation();
  const location = useLocation();
  const siteTitle = useGlobalStore(state => state.title);
  const menuMap = useMenuStore(state => state.menuMap);

  useEffect(() => {
    // 使用已格式化的标题，或回退到默认站点名
    const title = siteTitle || "Xin Admin";
    const menuKey = Object.keys(menuMap).find((key) => {
      return menuMap[key].path === location.pathname;
    });
    if (menuKey) {
      const menu = menuMap[menuKey];
      const documentTitle = menu.local ? t(menu.local) : menu.name;
      document.title = documentTitle + ' - ' + siteTitle;
    } else {
      document.title = title;
    }
  }, [location, t]);

  return null;
}

export default PageTitle;