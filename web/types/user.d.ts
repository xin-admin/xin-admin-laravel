declare namespace USER {
  interface UserLoginFrom {
    username?: string
    password?: string
    autoLogin?: boolean
    mobile?: string
    captcha?: number
    loginType?: LoginType
  }

  type LoginType = 'phone' | 'account' | 'email';

  interface MenuType {
    name: string;
    path: string;
    component: string;
    icon: string;
    children: MenuType[];
    key: string;
    local: string;
  }

  type AdminInfoResult = API.ResponseStructure<{
    menus: MenuType[],
    access: string[],
    info: UserInfo
  }>

  interface UserInfo {
    id?: string
    name?: string
    balance?: string
    nickname?: string
    username?: string
    email?: string
    avatar_id?: string
    mobile?: string
    motto?: string
    token?: string
    gender?: number
    refresh_token?:string
    avatar_url?: string
  }

  type LoginResult = API.ResponseStructure<{
    token: string
    refresh_token: string
  }>
}
