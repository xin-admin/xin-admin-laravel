import React, { useEffect } from "react";
import useGlobalStore from "@/stores/global";
import {useLocation} from "react-router";
import {useTranslation} from "react-i18next";
import useMenuStore from "@/stores/menu";

/**
 * 页面标题组件
 */
const PageTitle = (props: { children: React.ReactNode }) => {
  const children = props.children;
  const {t} = useTranslation();
  const location = useLocation();
  const routeMap = useMenuStore(data => data.routeMap);
  const defaultSiteName = useGlobalStore(state => state.title);

  useEffect(() => {
    const title = defaultSiteName || "Xin Admin";
    const routeKeys = Object.keys(routeMap).find(key => routeMap[key].path === location.pathname);
    if(!routeKeys) {
      document.title = title;
      return;
    }
    const route = routeMap[routeKeys];
    const pageTitle = route.local ? t(route.local) : route.name;
    if(pageTitle) {
      document.title = pageTitle+  ' - ' + title;
    } else {
      document.title = title;
    }

  }, [location.pathname, defaultSiteName, t, routeMap]);

  return children;
}

export default PageTitle;
