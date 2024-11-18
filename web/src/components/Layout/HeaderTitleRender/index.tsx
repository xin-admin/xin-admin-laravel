import { ProLayoutProps } from '@ant-design/pro-components';

/**
 * 自定义头标题的方法，mix 模式和 top 模式下生效
 * @param logo
 * @param title
 * @param props
 * @constructor
 */
const HeaderTitleRender: ProLayoutProps['headerTitleRender'] = (logo, title, props) => {
  const defaultDom = (
    <a>
      {logo}
      {title}
    </a>
  );
  if (typeof window === 'undefined') return defaultDom;
  if (document.body.clientWidth < 1400) {
    return defaultDom;
  }
  if (props.isMobile) return defaultDom;
  return (
    <div>
      {defaultDom}
    </div>
  );
}

export default HeaderTitleRender;