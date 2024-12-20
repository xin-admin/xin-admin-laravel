import { useModel } from '@umijs/max';
import React from 'react';

interface PropsTypes {
  auth?: string;
  children: React.ReactNode;
}

export default ( props: PropsTypes ) => {

  const {access} = useModel('userModel', (model) => ({
    access: model.userAccess
  }));

  const buttonAccess = (name?: string) => {
    // TODO 开发过程全部开放权限
    return true;
    if( name) {
      return access.includes(name.toLowerCase())
    }else {
      return true;
    }
  }

  return (
    <>
      { buttonAccess(props.auth) ? props.children : null }
    </>
  );
}
