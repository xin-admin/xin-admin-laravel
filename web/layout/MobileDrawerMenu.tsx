import React, {useState} from 'react';
import { Button, Drawer, Space } from 'antd';
import useGlobalStore from '@/stores/global';
import MenuRender from '@/layout/MenuRender';
import {GithubOutlined, HomeOutlined, MenuFoldOutlined, MenuUnfoldOutlined, SettingOutlined} from '@ant-design/icons';
import LanguageSwitcher from '@/components/LanguageSwitcher';

/**
 * 移动端抽屉菜单组件
 */
const MobileDrawerMenu: React.FC = () => {
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const themeDrawer = useGlobalStore(state => state.themeDrawer);
  const setThemeDrawer = useGlobalStore(state => state.setThemeDrawer);
  const [collapsed, setCollapsed] = useState<boolean>(false);

  return (
    <div>
      <div className="fixed bottom-8 left-8 z-999">
        <Button
          type={'primary'}
          shape="circle"
          size='large'
          icon={collapsed ? <MenuFoldOutlined/> : <MenuUnfoldOutlined/>}
          onClick={() => setCollapsed(!collapsed)}
        />
      </div>
      <Drawer
        placement="left"
        closable={true}
        onClose={() => setCollapsed(false)}
        open={collapsed}
        styles={{
          section: {width: 280},
          header: {
            borderBottom: '1px solid ' + themeConfig.colorBorder,
            background: themeConfig.siderBg,
            color: themeConfig.siderColor,
          },
          body: {
            padding: 0,
            background: themeConfig.siderBg,
          },
        }}
        footer={(
          <Space>
            <Button icon={<HomeOutlined/>} size={'large'} type={'text'}
                    onClick={() => window.open('https://xin-admin.com')}/>
            <Button icon={<GithubOutlined/>} size={'large'} type={'text'}
                    onClick={() => window.open('https://github.com/xin-admin/xin-admin-ui')}/>
            <LanguageSwitcher size={"large"} type={'text'} />
            <Button onClick={() => setThemeDrawer(!themeDrawer)} icon={<SettingOutlined/>} size={"large"} type={'text'}/>
          </Space>
        )}
      >
        <div
          style={{
            height: '100%',
            overflowY: 'auto',
            width: '100%',
            background: themeConfig.siderBg,
          }}
        >
          <MenuRender />
        </div>
      </Drawer>
    </div>
  );
};

export default MobileDrawerMenu;
