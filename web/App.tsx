import createRouter from "@/router";
import {RouterProvider} from "react-router";
import useGlobalStore from "@/stores/global";
import AntdProvider from "@/components/AntdProvider";
import {useEffect} from "react";
import useLanguage from '@/hooks/useLanguage';
import useMenuStore from "@/stores/menu";
import useAuthStore from "@/stores/user";
import useDictStore from "@/stores/dict";

const App = () => {
  const { changeLanguage } = useLanguage();
  const fetchUser = useAuthStore(state => state.info);
  const fetchMenu = useMenuStore(state => state.menu);
  const initWebInfo = useGlobalStore(state => state.initWebInfo);
  const initDict = useDictStore(state => state.initDict);

  const initData = async () => {
    // 初始化网站信息
    initWebInfo();
    // 初始化字典数据
    await initDict();
    // 初始化多语言信息
    await changeLanguage(localStorage.getItem('i18nextLng') || 'zh');
    // 初始化用户数据
    const isLoggedIn = !!localStorage.getItem('token');
    if (isLoggedIn) {
      await fetchUser();
      await fetchMenu();
    } else {
      if (window.location.pathname !== '/login') {
        window.location.href = '/login';
      }
    }
  }

  // 执行初始化
  useEffect(() => { initData() }, []);

  return (
    <AntdProvider>
      <RouterProvider router={createRouter()} />
    </AntdProvider>
  );
};

export default App;
