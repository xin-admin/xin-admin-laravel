import type {LoginParams} from "@/api/sys/sysUser.ts";

export interface AuthStoreState {
  userinfo: any;
  access: any[];
  initialized: boolean;
}

export interface AuthStoreActions {
  login: (credentials: LoginParams) => Promise<void>;
  logout: () => Promise<void>;
  userId: () => number;
  info: () => Promise<void>;
  setAccess: (assess: string[]) => void;
}

export type AuthStore = AuthStoreState & AuthStoreActions;