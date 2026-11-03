import useAuthStore from "@/stores/user";

function useAuth() {
  const access = useAuthStore(state => state.access);

  const auth = (key: string) => access.includes(key);

  return {auth};
}

export default useAuth;