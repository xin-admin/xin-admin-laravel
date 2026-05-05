import {Breadcrumb, type BreadcrumbProps} from "antd";
import {useEffect, useMemo, useState} from "react";
import {HomeOutlined} from "@ant-design/icons";
import IconFont from "@/components/IconFont";
import {useTranslation} from "react-i18next";
import {useLocation} from "react-router";
import {useLayoutContext} from "@/layout/LayoutContext";
import {buildBreadcrumbMap, findMenuByPath} from "@/layout/utils.ts";

const defaultBreadcrumb: BreadcrumbProps['items'] = [
  {
    title: <HomeOutlined />,
  }
];


const BreadcrumbRender = () => {
  const {t} = useTranslation();
  const location = useLocation();
  const { menus } = useLayoutContext();
  const [breadcrumbItems, setBreadcrumbItems] = useState<BreadcrumbProps['items']>(defaultBreadcrumb);

  const breadcrumbMap = useMemo(() => buildBreadcrumbMap(menus), [menus]);

  useEffect(() => {
    const path = location.pathname;
    const menu = findMenuByPath(menus, path);
    if(menu && menu.key) {
      const breadcrumbs = breadcrumbMap[menu.key];
      if(breadcrumbs) {
        setBreadcrumbItems([
          {
            title: <HomeOutlined />,
          },
          ...breadcrumbs.map(item => ({
            title: (
              <>
                {item.icon && <IconFont name={item.icon} />}
                <span>{item.local ? t(item.local) : item.title}</span>
              </>
            ),
          }))
        ]);
        return;
      }
    }
    setBreadcrumbItems(defaultBreadcrumb)
  }, [t, location.pathname, breadcrumbMap]);

  return (
    <Breadcrumb items={breadcrumbItems}/>
  )
}

export default BreadcrumbRender;
