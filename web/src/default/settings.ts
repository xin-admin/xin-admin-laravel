import { ProLayoutProps } from '@ant-design/pro-components';

/**
 * 默认 Layout
 */
export const Settings: ProLayoutProps & {
  pwa?: boolean;
  logo?: string;
} = {
  navTheme: 'light',
  colorPrimary: '#1890ff',
  layout: 'top',
  contentWidth: 'Fixed',
  fixedHeader: true,
  fixSiderbar: true,
  colorWeak: false,
  title: 'Xin Admin',
  pwa: true,
  logo: 'https://gw.alipayobjects.com/zos/rmsportal/KDpgvguMpGfqaHPjicRK.svg',
  iconfontUrl: '',
};

/**
 * 前台 Layout
 */
export const appSettings: ProLayoutProps  = {
  navTheme: 'light',
  layout: 'top',
  contentWidth: 'Fixed',
  fixedHeader: true,
  token: {
    pageContainer: {
      paddingBlockPageContainerContent: 0,
      paddingInlinePageContainerContent: 0,
    }
  },
  fixSiderbar: true,
  splitMenus: false,
  siderMenuType: "sub"
};

/**
 * 后台
 */
export const adminSettings: ProLayoutProps = {
  navTheme: 'light',
  layout: 'side',
  contentWidth: 'Fluid',
  fixedHeader: true,
  token: {
    pageContainer: {
      paddingBlockPageContainerContent: 24,
      paddingInlinePageContainerContent: 24,
    },
  },
  fixSiderbar: true,
  splitMenus: false,
}
