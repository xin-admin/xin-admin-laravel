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

  interface UpdatePassword {
    oldPassword: string
    newPassword: string
    rePassword: string
  }


  interface MenuType {
    name: string;
    path: string;
    component: string;
    icon: string;
    children: MenuType[];
    key: string;
    locale: string;
  }


  interface AdminInfo {
    id?: string
    name?: string
    money?: string
    nickname?: string
    username?: string
    email?: string
    avatar?: string
    mobile?: string
    motto?: string
    token?: string
    gender?: number
    refresh_token?:string
    avatar_url?: string
  }

  type AdminInfoResult = API.ResponseStructure<{
    menus: MenuType[],
    access: string[],
    info: AdminInfo
  }>

  interface UserInfo {
    id?: string
    name?: string
    money?: string
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

  type UserInfoResult = API.ResponseStructure<{
    menus: MenuType[],
    access: string[],
    info: UserInfo
  }>

  type LoginResult = API.ResponseStructure<{
    token: string
    refresh_token: string
  }>

  type ReToken = {
    data: {
      token: string
    }
    success: boolean
  }
}
