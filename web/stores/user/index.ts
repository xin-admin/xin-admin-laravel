import {create, type StateCreator} from 'zustand';
import {createJSONStorage, devtools, persist} from "zustand/middleware";
import {info, type InfoResponse, type LoginParams, logout} from "@/api/system/sys_user.ts";
import {login} from "@/api/system/sys_user";
import type {AuthStore, AuthStoreState, AuthStoreActions} from "./types";

const authState: AuthStoreState = {
  userinfo: {},
  access: [],
};

const authAction: StateCreator<AuthStore, [], [], AuthStoreActions> = (set, get) => ({
  userId: () => get().userinfo.id,
  setAccess: (access) => set({access}),
  login: async (credentials: LoginParams) => {
    const { data } = await login(credentials);
    localStorage.setItem("token", data.data!.token);
  },
  logout: async () => {
    await logout();
    localStorage.removeItem("token");
    set(authState);
  },
  info: async () => {
    const result = await info();
    const data: InfoResponse = result.data.data!;
    set({
      userinfo: data.info,
      access: data.access,
    });
  },
})

const useUserStore = create<AuthStore>()(
  devtools(
    persist(
      (...args) => ({
        ...authState,
        ...authAction(...args),
      }),
      {
        name: 'auth-storage',
        storage: createJSONStorage(() => localStorage),
      }
    ),
    { name: 'XinAdmin-Auth' }
  )
);

export type {AuthStore, AuthStoreState, AuthStoreActions};
export default useUserStore;
