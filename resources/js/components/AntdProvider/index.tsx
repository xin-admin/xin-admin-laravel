import {App, ConfigProvider, theme as antTheme, type ThemeConfig} from 'antd';
import {type PropsWithChildren, useMemo} from 'react';
import '@ant-design/v5-patch-for-react-19';
import {useGlobalStore} from "@/stores";

import { useAntdLocale } from '@/hooks/useLanguage';

function ContextHolder() {
  const { message, modal, notification } = App.useApp();
  window.$message = message;
  window.$modal = modal;
  window.$notification = notification;
  return null;
}

const AppProvider = ({ children }: PropsWithChildren) => {
  const themeConfig = useGlobalStore(state => state.themeConfig);
  const locale = useAntdLocale();

  const theme: ThemeConfig = useMemo(() => ({
    components: {
      Layout: {
        siderBg: 'transparent',
      },
      Menu: {
        activeBarBorderWidth: 0,
        subMenuItemBg: 'transparent',
        itemBg: 'transparent',
      }
    },
    token: {
      colorPrimary: themeConfig.colorPrimary,
    },
    algorithm: themeConfig.themeScheme === 'light' ? antTheme.defaultAlgorithm : antTheme.darkAlgorithm
  }), [themeConfig])

  return (
    <ConfigProvider theme={{...theme, cssVar: true}} locale={locale}>
      <App>
        <ContextHolder />
        {children}
      </App>
    </ConfigProvider>
  );
};

export default AppProvider;