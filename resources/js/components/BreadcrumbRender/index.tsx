import {Breadcrumb, type BreadcrumbProps} from "antd";
import {useEffect, useState} from "react";
import {HomeOutlined} from "@ant-design/icons";
import {useTranslation} from "react-i18next";
import { useLocation } from 'react-router';
import useMenuStore from "@/stores/menu";

const defaultBreadcrumbItems = [
  {
    href: '/',
    title: <HomeOutlined />,
  }
];

const BreadcrumbRender = () => {
  const {t} = useTranslation();
  const location = useLocation();
  const breadcrumbMap = useMenuStore(state => state.breadcrumbMap);
  const [breadcrumbItems, setBreadcrumbItems] = useState<BreadcrumbProps['items']>(defaultBreadcrumbItems);

  useEffect(() => {
    setBreadcrumbItems([
      ...defaultBreadcrumbItems,
      ...breadcrumbMap[location.pathname].map(item => ({
        title: (
          <span>{item.local ? t(item.local) : item.title}</span>
        )
      }))
    ])
  }, [location, t]);

  return (
    <Breadcrumb items={breadcrumbItems} />
  )
}

export default BreadcrumbRender;