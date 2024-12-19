import React, { CSSProperties, useEffect, useMemo, useState } from 'react';
import { ConfigProvider, MenuProps } from 'antd';
import { Menu } from 'antd';
import { useModel, history, FormattedMessage } from '@umijs/max';
import IconFont from '@/components/IconFont';
import * as AntdIcons from '@ant-design/icons';

type MenuItem = Required<MenuProps>['items'][number];
const allIcons: { [key: string]: any } = AntdIcons;

const menuItemRender = (menus: USER.MenuType[]): MenuItem[] => {
  let menuItems: MenuItem[] = [];
  menus.forEach((item) => {
    let menuItem: MenuItem = {
      key: item.path,
      label: item.local ? <FormattedMessage id={item.local} /> : item.name,
    }
    if(item.children) {
      // @ts-ignore
      menuItem.children = menuItemRender(item.children)
    }
    if (item.icon) {
      if (item.icon.startsWith('icon-')) {
        menuItem.icon = <IconFont name={item.icon}/>;
      } else {
        menuItem.icon = React.createElement(allIcons[item.icon]);
      }
    }
    menuItems.push(menuItem)
  })
  return menuItems;
}

const MenuRender: React.FC = () => {
  const menus = useModel('userModel',  ({menus}) => menus)
  const collapsed = useModel('@@initialState', ({initialState}) => initialState?.collapsed)

  const items: MenuItem[] = useMemo(() => {
    return menuItemRender(menus);
  }, [menus]);

  const onClick: MenuProps['onClick'] = (e) => {
    history.push(e.key);
  };

  const [width, setWidth] = useState<number>(228);
  useEffect(() => setWidth(collapsed ? 56 : 228), [collapsed]);


  const menuBoxStyle: CSSProperties = {
    width: width,
    overflow: 'hidden',
    flex: `0 0 ${width}px`,
    maxWidth: width,
    minWidth: width,
    transition: '0.2s'
  }

  const menuAsideStyle: CSSProperties = {
    borderInlineEnd: '1px solid rgba(5, 5, 5, 0.06)',
    height: 'calc(100% - 56px)',
    transition: '0.2s',
    insetBlockStart: 56,
    flex: `0 0 ${width}px`,
    maxWidth: width,
    minWidth: width,
    width: width,
    position: 'fixed',
    insetInlineStart: 0,
    zIndex: 100,
    display: 'flex',
    flexDirection: 'column',
  }

  const menuFooterStyle: CSSProperties = {
    textAlign: 'center',
    paddingBlockStart: 12,
    color: 'rgba(0, 0, 0, 0.45)',
    paddingBlockEnd: 12,
    fontSize: '14px',
    animationName: 'antBadgeLoadingCircle',
    animationDuration: '0.2s',
    animationTimingFunction: 'ease'
  }

  return (
    <ConfigProvider theme={{ components: {Menu: {collapsedWidth: 56}}}}>
      <div style={menuBoxStyle}></div>
      <aside style={menuAsideStyle}>
        <div style={{ flex: '1 1 0%', overflow: 'hidden auto' }}>
          <Menu
            onClick={onClick}
            defaultSelectedKeys={['/dashboard/analysis']}
            defaultOpenKeys={['/dashboard']}
            mode="inline"
            items={items}
            inlineCollapsed={collapsed}
          />
        </div>
        {collapsed ? null :
          <div style={menuFooterStyle}>
            <div>© 2024 Made with love</div>
            <div>by Xin Admin</div>
          </div>
        }
      </aside>

    </ConfigProvider>
  );
};

export default MenuRender;
