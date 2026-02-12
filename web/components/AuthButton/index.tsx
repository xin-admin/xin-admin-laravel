import { type ReactNode, useMemo } from 'react';
import useAuthStore from "@/stores/user";

interface AuthButtonProps {
  auth?: string;
  children: ReactNode;
}

const AuthButton = ({ auth, children }: AuthButtonProps) => {
  const access = useAuthStore(state => state.access);

  const hasPermission = useMemo(() => {
    // 未指定权限，默认显示
    if (!auth) return true;
    
    // 检查权限
    return access.includes(auth);
  }, [access, auth]);

  // 无权限时不渲染
  if (!hasPermission) return null;

  return <>{children}</>;
};

export default AuthButton;