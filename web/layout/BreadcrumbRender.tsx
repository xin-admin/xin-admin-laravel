import {Breadcrumb, type BreadcrumbProps} from "antd";
import {useEffect, useState} from "react";
import {HomeOutlined} from "@ant-design/icons";
import IconFont from "@/components/IconFont";
import {useTranslation} from "react-i18next";
import useMenuStore from "@/stores/menu";
import {useLocation} from "react-router";

const defaultBreadcrumb: BreadcrumbProps['items'] = [
  {
    title: <HomeOutlined />,
  }
]

const BreadcrumbRender = () => {
  const {t} = useTranslation();
  const location = useLocation();
  const breadcrumbMap = useMenuStore(state => state.breadcrumbMap);
  const pathMap = useMenuStore(state => state.pathMap);
  const [breadcrumbItems, setBreadcrumbItems] = useState<BreadcrumbProps['items']>(defaultBreadcrumb);
  useEffect(() => {
    const path = location.pathname;
    const keys = Object.keys(pathMap).find(key => pathMap[key] === path);
    if(keys) {
      const breadcrumbs = breadcrumbMap[keys];
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
  }, [pathMap, t, location, breadcrumbMap]);

  return (
    <Breadcrumb items={breadcrumbItems}/>
  )
}

export default BreadcrumbRender;