import noAuthRouter from '@/default/noAuthRouter';

/**
 * @see https://umijs.org/zh-CN/plugins/plugin-access
 * */
export default (initialState:initialStateType) => {
  const access: string[] = []
  if(initialState && initialState.access){
    access.push(...initialState.access.map(item=>item.toLowerCase()))
  }
  return {
    buttonAccess: (name:string) => access.includes(name.toLowerCase()),
    urlAccess: (name:string) => {
      let accessName = name.slice(1).toLowerCase().replace(/\//g,'.');
      return access.includes(accessName) || noAuthRouter.includes(name)
    },
  }
}
