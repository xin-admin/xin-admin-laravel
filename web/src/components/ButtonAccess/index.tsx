import { useModel } from '@umijs/max';
import React from 'react';

interface PropsTypes {
  auth: string;
  children: React.ReactNode;
}

export default ( props: PropsTypes ) => {

  const {access} = useModel('userModel', (model) => ({
    access: model.userAccess
  }));

  const buttonAccess = (name:string) => {
    return access.includes(name.toLowerCase())
  }

  return (
    <>
      { buttonAccess(props.auth) ? props.children : null }
    </>
  );
}
