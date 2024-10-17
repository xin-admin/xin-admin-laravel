import { Access, Link, useAccess, useLocation } from '@umijs/max';
import { Result } from 'antd';
import React from 'react';


export  default  (
  props: {children: React.ReactNode}
) => {
  const access = useAccess();
  const { pathname } = useLocation();

  return (
    <Access accessible={access.urlAccess(pathname)} fallback={(
      <Result
        status='403'
        title='403'
        subTitle='抱歉, 你暂时没有此页面的权限.'
        extra={<Link to='/'>去首页</Link>}
      />
    )}>
      {props.children}
    </Access>
  );
}
