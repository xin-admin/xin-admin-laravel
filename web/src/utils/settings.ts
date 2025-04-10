import { ProLayoutProps } from '@ant-design/pro-components';

/**
 * 默认布局设置
 */
const settings: ProLayoutProps = {
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
  ]
}

export default settings
