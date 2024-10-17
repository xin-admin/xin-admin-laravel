/**
 * 全局初始化状态
 */
import { appSettings } from './settings';
import { getAdminInfo } from '@/services/admin';
import { getUserInfo } from '@/services/api/user';

const fetchAdminInfo = async () => {
  const msg = await getAdminInfo();
  return msg.data;
};
const fetchUserInfo = async () => {
  const msg = await getUserInfo();
  return msg.data;
};

const defaultInitialState: initialStateType  = {
  access: [],
  isLogin: false,
  isAccess: false,
  drawerShow: false,
  fetchUserInfo,
  fetchAdminInfo,
  currentUser: {},
  settings: appSettings,
  borderShow: true,
  app: localStorage.getItem('app') === 'admin' ? 'admin' : 'api',
  webSetting: {
    logo: 'https://file.xinadmin.cn/file/favicons.ico',
    title: 'Xin Admin'
  }
}

export default defaultInitialState;
