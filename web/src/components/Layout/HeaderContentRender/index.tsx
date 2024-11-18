import { Button } from 'antd';
import { MenuFoldOutlined, MenuUnfoldOutlined } from '@ant-design/icons';
import { useModel } from '@umijs/max';

/**
 * 自定义头内容的渲染
 */
const headerContentRender = () => {
  const {initialState, setInitialState} = useModel('@@initialState');

  return (
    <div style={{display: 'flex', alignItems: 'center'}}>
      <Button
        type={'text'}
        style={{marginRight: 20}}
        icon={ initialState?.collapsed ? <MenuUnfoldOutlined /> : <MenuFoldOutlined />}
        onClick={() => {
          setInitialState({...initialState! , collapsed: !initialState?.collapsed})
        }}
      />
      <div>欢迎进入 XinAdmin 企业级管理系统</div>
    </div>
  )
}

export default headerContentRender;