import { ProLayoutProps } from '@ant-design/pro-components';
import { useModel } from '@umijs/max';
import { CSSProperties } from 'react';

/**
 * 自定义头标题的方法，mix 模式和 top 模式下生效
 * @param logo
 * @param title
 * @param props
 * @constructor
 */
const HeaderTitleRender: ProLayoutProps['headerTitleRender'] = (logo, title, props) => {
  const {initialState} = useModel('@@initialState')

  const titleDivStyle: CSSProperties = {
    display: 'flex',
    alignItems: 'center'
  }

  return (
    <div style={titleDivStyle}>
      <img src={ initialState?.webSetting?.logo } alt="logo" />
      <h1 style={{fontSize: 18}}>{ initialState?.webSetting?.title }</h1>
    </div>
  )
}

export default HeaderTitleRender;
