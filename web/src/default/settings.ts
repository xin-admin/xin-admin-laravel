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
  bgLayoutImgList: [
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/D2LWSqNny4sAAAAAAAAAAAAAFl94AQBr',
      left: 85,
      bottom: 100,
      height: '303px',
    },
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/C2TWRpJpiC0AAAAAAAAAAAAAFl94AQBr',
      bottom: -68,
      right: -45,
      height: '303px',
    },
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/F6vSTbj8KpYAAAAAAAAAAAAAFl94AQBr',
      bottom: 0,
      left: 0,
      width: '331px',
    },
  ],
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
  siderMenuType: "sub",
  bgLayoutImgList: [
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/D2LWSqNny4sAAAAAAAAAAAAAFl94AQBr',
      left: 85,
      bottom: 100,
      height: '303px',
    },
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/C2TWRpJpiC0AAAAAAAAAAAAAFl94AQBr',
      bottom: -68,
      right: -45,
      height: '303px',
    },
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/F6vSTbj8KpYAAAAAAAAAAAAAFl94AQBr',
      bottom: 0,
      left: 0,
      width: '331px',
    },
  ],
};

/**
 * 后台
 */
export const adminSettings: ProLayoutProps = {
  navTheme: 'light',
  layout: 'mix',
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
  bgLayoutImgList: [
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/D2LWSqNny4sAAAAAAAAAAAAAFl94AQBr',
      left: 85,
      bottom: 100,
      height: '303px',
    },
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/C2TWRpJpiC0AAAAAAAAAAAAAFl94AQBr',
      bottom: -68,
      right: -45,
      height: '303px',
    },
    {
      src: 'https://mdn.alipayobjects.com/yuyan_qk0oxh/afts/img/F6vSTbj8KpYAAAAAAAAAAAAAFl94AQBr',
      bottom: 0,
      left: 0,
      width: '331px',
    },
  ],
}
