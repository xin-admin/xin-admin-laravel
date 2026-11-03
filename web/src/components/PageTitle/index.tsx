import React, { useEffect } from "react";
import useGlobalStore from "@/stores/global";
import {useLocation} from "react-router";
import {useTranslation} from "react-i18next";
import {useLayoutContext} from "@/layout/LayoutContext";
import {findMenuByPath} from "@/layout/utils.ts";

const PageTitle: React.FC = () => {
  const {t} = useTranslation();
  const location = useLocation();
  const defaultSiteName = useGlobalStore(state => state.title);
  const { menus } = useLayoutContext();

  useEffect(() => {
    const title = defaultSiteName || "Xin Admin";
    const menu = findMenuByPath(menus, location.pathname);
    if(!menu) {
      document.title = title;
      return;
    }
    const pageTitle = menu.local ? t(menu.local) : menu.name;
    if(pageTitle) {
      document.title = pageTitle+  ' - ' + title;
    } else {
      document.title = title;
    }

  }, [location.pathname, defaultSiteName, t, menus]);

  return null;
}

export default PageTitle;
