import type {LoginParams} from "@/api/system/sys_user";

export interface AuthStoreState {
  userinfo: any;
  access: any[];
}

export interface AuthStoreActions {
  login: (credentials: LoginParams) => Promise<void>;
  logout: () => Promise<void>;
  userId: () => number;
  info: () => Promise<void>;
  setAccess: (assess: string[]) => void;
}

export type AuthStore = AuthStoreState & AuthStoreActions;
