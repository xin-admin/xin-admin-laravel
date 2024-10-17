import {useLocation, useNavigate, FormattedMessage} from '@umijs/max'
import {ReactNode, useEffect, useState} from "react";
import {getMenuData} from "@ant-design/pro-components";
import type { TabsProps } from 'antd';
import './index.less';
import { useModel } from '@umijs/max';
import { ConfigProvider, Tabs } from 'antd';
const XinTabs = (props: {children: ReactNode}) => {
  const location = useLocation();
  const navigate = useNavigate();
  const [activeKey, setActiveKey] = useState<string>('');
  const [tabsItem, setTabsItem] = useState<Exclude<TabsProps['items'], undefined>>([
    {key: '/dashboard/analysis', label: <FormattedMessage id="menu.dashboard.analysis" />, closeIcon: null}
  ]);

  const {initialState} = useModel('@@initialState');

  // @ts-ignore
  const {breadcrumb } = getMenuData(initialState?.menus);

  useEffect(() => {
    const menuDataItem = breadcrumb[location.pathname];
    if(!menuDataItem) return
    const inTabs = tabsItem.some((item) => {
      return item.key === location.pathname
    })
    if (!inTabs) {
      setTabsItem([...tabsItem, {
        key: location.pathname,
        label: menuDataItem.locale?<FormattedMessage id={menuDataItem.local} />: menuDataItem.name
      }]);
    }
    setActiveKey(location.pathname)
  }, [location])

  const onEdit: TabsProps['onEdit'] = (key, action) => {
    if (action === 'remove') {
      let itemIndex = tabsItem.findIndex((item) => item.key === key)
      tabsItem.splice(itemIndex, 1)
      let obj = tabsItem[itemIndex === tabsItem.length ? itemIndex - 1 : itemIndex]
      setActiveKey(obj.key)
      navigate(obj.key, {replace: true})
      setTabsItem([...tabsItem])
    }
  }

  const onClick: TabsProps['onTabClick'] = (key) => {
    navigate(key, {replace: true})
  }


  const tabProps: TabsProps = {
    size: 'large',
    activeKey: activeKey,
    type: "editable-card",
    tabBarGutter: 5,
    hideAdd: true,
    items: tabsItem,
    onTabClick: onClick,
    onEdit: onEdit,
    id: 'xin-tabs'
  }

  return (
    <ConfigProvider
      theme={{
        components: {
          Tabs: {
            cardGutter: 100
          },
        },
      }}
    >
      <Tabs {...tabProps}/>
      {props.children}
    </ConfigProvider>
  )
}

export default XinTabs
