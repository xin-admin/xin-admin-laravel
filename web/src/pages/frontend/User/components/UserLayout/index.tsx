import React from 'react';
import { UserOutlined } from '@ant-design/icons';
import type { MenuProps, ThemeConfig } from 'antd';
import { Affix, Avatar, Col, ConfigProvider, Menu, Row, Space } from 'antd';
import { useModel, useNavigate, FormattedMessage, useLocation } from '@umijs/max';

type MenuItem = Required<MenuProps>['items'][number];

const getItem = (menus?: USER.MenuType[]): MenuItem[] => {
  let data: MenuItem[] | undefined = menus?.map((item) => {
    if (item.children) {
      let children: MenuItem[] = getItem(item.children);
      return {
        key: item.path,
        children: children,
        type: 'group',
        label: item.locale ? <FormattedMessage id={item.locale} /> : item.name
      }
    }
    return {
      key: item.path,
      label: item.locale ? <FormattedMessage id={item.locale} /> : item.name
    }
  });
  if (!data) return [];
  return data;
};

export default (props: { children: React.ReactNode}) => {
  const { pathname } = useLocation()
  const {children} = props
  let navigate = useNavigate();
  const { initialState } = useModel('@@initialState');

  const menuConfig: ThemeConfig = {
    components: {
      Menu: {
        activeBarWidth: 3,
        itemMarginBlock: 0,
        itemMarginInline: 0,
        itemBorderRadius: 0
      }
    }
  }

  return (
    <Row wrap={false} gutter={20} style={{ maxWidth: 1460, width: '100%', margin: '0 auto',paddingTop: 20 }}>
      <Col flex={'260px'}>
        <Affix offsetTop={70}>
          <div style={{background: '#fff',paddingBottom: 20}}>
            <Space style={{padding: 20, borderBottom: '1px solid #eee', width: '100%'}}>
              <Avatar icon={<UserOutlined />} size={52} src={initialState!.currentUser!.avatar_url} />
              <div>
                <div>{initialState!.currentUser!.nickname || initialState!.currentUser!.name || initialState!.currentUser!.username}</div>
                <div style={{ marginTop: '10px', color: '#8a919f' }}>一个热爱开发的同学</div>
              </div>
            </Space>
            <ConfigProvider theme={menuConfig}>
              <Menu
                style={{ border: 'none' }}
                mode='inline'
                defaultSelectedKeys={['index']}
                items={getItem(initialState!.menus!.find((item) => item.key === 'user')!.children)}
                selectedKeys={[pathname]}
                onSelect={(data) => {
                  navigate(data.key);
                }}
              />
            </ConfigProvider>
          </div>
        </Affix>
      </Col>
      <Col flex={'auto'}>
        {children}
      </Col>
    </Row>
  );
}
