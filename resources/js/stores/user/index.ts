import {create, type StateCreator} from 'zustand';
import {createJSONStorage, devtools, persist} from "zustand/middleware";
import {info, type InfoResponse, type LoginParams, logout} from "@/api/sys/sysUser";
import {login} from "@/api/sys/sysUser";
import type {AuthStore, AuthStoreState, AuthStoreActions} from "./types";

const authState: AuthStoreState = {
  userinfo: {},
  access: [],
  initialized: false,
};

const authAction: StateCreator<AuthStore, [], [], AuthStoreActions> = (set, get) => ({
  userId: () => get().userinfo.id,
  setAccess: (access) => set({access}),
  login: async (credentials: LoginParams) => {
    const { data } = await login(credentials);
    localStorage.setItem("token", data.data!.token);
    set({ initialized: true });
  },
  logout: async () => {
    await logout();
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