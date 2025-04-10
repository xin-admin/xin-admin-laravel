import { Link, useLocation, useModel } from '@umijs/max';
import { Result } from 'antd';
import React from 'react';
import noAuthRouter from '@/utils/noPermission';

export  default  (
  props: {children: React.ReactNode}
) => {
  const {access} = useModel('userModel', (model) => ({
    access: model.userAccess
  }));
  const { pathname } = useLocation();
  const urlAccess = (name:string) => {
    let accessName = name.slice(1).replace(/\//g,'.');
    return access.includes(accessName) || noAuthRouter.includes(name)
  }

  return (
    <>
      { urlAccess(pathname) ?
        props.children :
        <Result
          status='403'
          title='403'
          subTitle='抱歉, 你暂时没有此页面的权限.'
          extra={<Link to='/'>去首页</Link>}
        />
      }
    </>
  );
}
