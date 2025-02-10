import { Button } from 'antd';
import { MenuFoldOutlined, MenuUnfoldOutlined } from '@ant-design/icons';
import { FormattedMessage, useModel } from '@umijs/max';

/**
 * 自定义头内容的渲染
 */
const headerContentRender = () => {
  const {collapsed, setInitialState} = useModel('@@initialState', ({initialState, setInitialState}) => {
    return {
      collapsed: initialState?.collapsed,
      setInitialState
    }
  });
  return (
    <div style={{display: 'flex', alignItems: 'center'}}>
      <Button
        type={'text'}
        style={{marginRight: 20}}
        icon={ collapsed ? <MenuUnfoldOutlined /> : <MenuFoldOutlined />}
        onClick={() => {
          setInitialState((init) => {
            return {
              ...init,
              collapsed: !init?.collapsed,
            }
          })
        }}
      />
      <div><FormattedMessage id={'app.welcome'}/></div>
    </div>
  )
}

export default headerContentRender;
