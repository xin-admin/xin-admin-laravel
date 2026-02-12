import createRouter from "@/router";
import {RouterProvider, type RouterProviderProps} from "react-router";
import useGlobalStore from "@/stores/global";
import AntdProvider from "@/components/AntdProvider";
import {useEffect, useState} from "react";
import useLanguage from '@/hooks/useLanguage';
import useMenuStore from "@/stores/menu";
import useAuthStore from "@/stores/user";
import Loading from "@/components/Loading";
// import defaultRoute from "@/router/default";

const App = () => {
  const { changeLanguage } = useLanguage();
  const fetchUser = useAuthStore(state => state.info);
  const fetchMenu = useMenuStore(state => state.menu);
  const initWebInfo = useGlobalStore(state => state.initWebInfo);
  const [routes, setRoutes] = useState<RouterProviderProps['router']>();

  const initData = async () => {
    // 初始化网站信息
    initWebInfo();
    // 初始化多语言信息
    await changeLanguage(localStorage.getItem('i18nextLng') || 'zh');
    // 初始化用户数据
    const isLoggedIn = !!localStorage.getItem('token');
    if (isLoggedIn) {
      await fetchUser()
      const menus = await fetchMenu();
      setRoutes(createRouter(menus));
    } else {
      setRoutes(createRouter([]))
      if (window.location.pathname !== '/login') {
        window.location.href = '/login';
      }
    }
  }

  // 执行初始化
  useEffect(() => { initData() }, []);

  return (
    <AntdProvider>
      { routes ? (
        <RouterProvider router={routes} />
      ) : (
        <Loading />
      )}
    </AntdProvider>
  );
};

export default App;
