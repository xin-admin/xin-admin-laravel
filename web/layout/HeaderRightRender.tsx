import {Avatar, Button, Dropdown, Empty, Input, type MenuProps, message, Modal, Space, theme} from "antd";
import {
  ArrowDownOutlined,
  ArrowUpOutlined,
  EnterOutlined,
  FullscreenExitOutlined,
  FullscreenOutlined,
  GithubOutlined,
  HomeOutlined,
  SearchOutlined,
  SettingOutlined,
  UserOutlined,
  VerticalLeftOutlined
} from "@ant-design/icons";
import useGlobalStore from "@/stores/global";
import useAuthStore from "@/stores/user";
import {useTranslation} from "react-i18next";
import {useNavigate} from "react-router";
import {useState} from "react";
import LanguageSwitcher from "@/components/LanguageSwitcher";
import useMobile from "@/hooks/useMobile";

const {useToken} = theme;

const HeaderLeftRender = () => {
  const {token} = useToken();
  const {t} = useTranslation();
  const navigate = useNavigate()
  const themeDrawer = useGlobalStore(state => state.themeDrawer);
  const setThemeDrawer = useGlobalStore(state => state.setThemeDrawer);
  const userInfo = useAuthStore(state => state.userinfo);
  const logout = useAuthStore(state => state.logout);
  const isMobile = useMobile();
  const [fullscreen, setFullscreen] = useState<boolean>(false);
  const [searchOpen, setSearch] = useState<boolean>(false);

  /** 全屏按钮操作事件 */
  const onFullscreenClick = () => {
    /* 获取 documentElement (<html>) 以全屏显示页面 */
    const elem = document.documentElement;
    /* 全屏查看 */
    if (document.fullscreenElement) {
      document.exitFullscreen().then(() => {
        setFullscreen(false);
      });
    } else {
      elem.requestFullscreen().then(() => {
        setFullscreen(true);
      });
    }
  };

  const userItems: MenuProps['items'] = [
    {
      key: '1',
      label: t('layout.userSetting'),
      icon: <UserOutlined/> ,
      onClick: () => navigate('/user/setting')
    },
    {
      key: '2',
      label: t('layout.logout'),
      icon: <VerticalLeftOutlined />,
      onClick: async () => {
        logout().then(() => {
          message.success(t('layout.logoutSuccess'))
          window.location.href = '/login';
        }).catch(() => {
          message.error(t('layout.logoutFailed'))
        })
      },
    },
  ]

  return (
    <>
      <Modal
        closable={false}
        open={searchOpen}
        maskClosable
        footer={null}
        style={{top: 40}}
        styles={{content: {padding: 0, width: 600, maxHeight: '80vh'}}}
        onCancel={() => setSearch(false)}
      >
        <div className={'p-5'}>
          <Input size="large" placeholder={t('layout.searchPlaceholder')} prefix={<SearchOutlined/>}/>
          <div className={'mt-5'}>
            <Empty/>
            {/* TODO 可以帮忙实现菜单栏搜索 */}
          </div>
        </div>
        <Space className={'mt-5 flex align-center pl-5 pr-5 pt-2.5 pb-2.5'}
               style={{borderTop: '1px solid ' + token.colorBorder}}>
          <EnterOutlined/> <span className={'mr-4'}>{t('layout.searchConfirm')}</span>
          <ArrowUpOutlined/><ArrowDownOutlined/> <span className={'mr-4'}>{t('layout.searchSwitch')}</span>
          Esc <span className={'mr-2'}>{t('layout.searchClose')}</span>
        </Space>
      </Modal>
      <Space>
        { !isMobile && (
          <>
            <Button icon={<HomeOutlined/>} size={'large'} type={'text'}
                onClick={() => window.open('https://xin-admin.com')}/>
            <Button icon={<GithubOutlined/>} size={'large'} type={'text'}
                    onClick={() => window.open('https://github.com/xin-admin/xin-admin-ui')}/>
            <Button icon={<SearchOutlined/>} size={'large'} type={'text'} onClick={() => setSearch(true)}/>
            <Button
              onClick={onFullscreenClick}
              icon={fullscreen ? <FullscreenExitOutlined/> : <FullscreenOutlined/>}
              size={"large"}
              type={'text'}
            />
            <LanguageSwitcher size={"large"} type={'text'} />
            <Button onClick={() => setThemeDrawer(!themeDrawer)} icon={<SettingOutlined/>} size={"large"} type={'text'}/>
          </>
        )}
        {userInfo ?
          <Dropdown menu={{items: userItems}}>
            <Button size={"large"} type={'text'}>
              <div>{userInfo.nickname || userInfo.username}</div>
              <Avatar src={userInfo.avatar_url ? userInfo.avatar_url : null} size={'small'}/>
            </Button>
          </Dropdown>
          : null
        }
      </Space>
    </>
  )
}

export default HeaderLeftRender