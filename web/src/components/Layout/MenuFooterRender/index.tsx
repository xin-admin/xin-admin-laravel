import { ProLayoutProps } from '@ant-design/pro-components';

/**
 * 	在 menu 底部渲染一个块
 * @param props
 * @constructor
 */
const MenuFooterRender: ProLayoutProps['menuFooterRender'] = (props) => {
  if (props?.collapsed) return <></>;
  return (
    <div
      style={{
        textAlign: 'center',
        paddingBlockStart: 12,
      }}
    >
      <div>© 2024 Made with love</div>
      <div>by Xin Admin</div>
    </div>
  );
}

export default MenuFooterRender
