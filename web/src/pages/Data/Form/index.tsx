import { Card } from 'antd';
import LightFilter from './components/LightFilter';
import QueryFilter from './components/QueryFilter';
import LoginForm from './components/LoginForm';
import StepsForm from './components/StepsForm';
import ModalForm from './components/ModalForm';
import ProForm from './components/ProForm';
import {ReactNode, useState} from "react";




export default () => {

  const contentList: Record<string, ReactNode> = {
    ProForm: <ProForm/>,
    LightFilter: <LightFilter/>,
    QueryFilter: <QueryFilter/>,
    ModalForm: <ModalForm/>,
    StepsForm: <StepsForm/>,
    LoginForm: <LoginForm/>,
  };
  const [activeTabKey1, setActiveTabKey1] = useState<string>('LightFilter');

  const onTab1Change = (key: string) => {
    setActiveTabKey1(key);
  };

  const tableList = [
    {
      key: 'ProForm',
      label: '高级表单',
    },
    {
      key: 'LightFilter',
      label: '筛选表单',
    },
    {
      key: 'QueryFilter',
      label: '搜索表单',
    },
    {
      key: 'ModalForm',
      label: '浮层表单',
    },
    {
      key: 'StepsForm',
      label: '分步表单',
    },
    {
      key: 'LoginForm',
      label: '登录表单',
    },
  ]

  return (
    <Card  title={'高级表单'}
      activeTabKey={activeTabKey1}
      onTabChange={onTab1Change}
      tabList={tableList}
    >
      {contentList[activeTabKey1]}
    </Card>
  )
};
