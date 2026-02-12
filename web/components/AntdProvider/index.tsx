import {App, ConfigProvider, type ThemeConfig} from 'antd';
import {type PropsWithChildren, useMemo} from 'react';
import '@ant-design/v5-patch-for-react-19';
import algorithm from "@/layout/algorithm.ts";
import useGlobalStore from "@/stores/global";
import useLanguage from '@/hooks/useLanguage';

function ContextHolder() {
  const { message, modal, notification } = App.useApp();
  window.$message = message;
  window.$modal = modal;
  window.$notification = notification;
  return null;
}

const AppProvider = ({ children }: PropsWithChildren) => {
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const { antdLocale } = useLanguage();

  const theme: ThemeConfig = useMemo(() => ({
    components: {
      Layout: {
        headerPadding: "0 " + themeConfig.headerPadding + "px",
        headerHeight: themeConfig.headerHeight,
        bodyBg: themeConfig.bodyBg,
        footerBg: themeConfig.footerBg,
        headerBg: themeConfig.headerBg,
        headerColor: themeConfig.headerColor,
        siderBg: themeConfig.siderBg,
        footerPadding: 0
      },
      Menu: {
        activeBarBorderWidth: 0,
        itemBg: 'transparent',
        subMenuItemBg: 'transparent',
      }
    },
    token: {
      colorPrimary: themeConfig.colorPrimary,
      colorBgBase: themeConfig.colorBg,
      colorTextBase: themeConfig.colorText,
      colorError: themeConfig.colorError,
      colorInfo: themeConfig.colorPrimary,
      colorLink: themeConfig.colorPrimary,
      colorSuccess: themeConfig.colorSuccess,
      colorWarning: themeConfig.colorWarning,
      borderRadius: themeConfig.borderRadius,
      controlHeight: themeConfig.controlHeight,
    },
    algorithm: themeConfig.algorithm ? algorithm[themeConfig.algorithm] : undefined
  }), [themeConfig])

  return (
    <ConfigProvider theme={{...theme, cssVar: true}} locale={antdLocale}>
      <App>
        <ContextHolder />
        {children}
      </App>
    </ConfigProvider>
  );
};

export default AppProvider;