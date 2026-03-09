import menu from './menu';
import userSetting from "./user-setting";
import xinTable from "./xin-table";
import sysUserList from "./sys-user-list";
import sysUserDept from "./sys-user-dept";
import sysUserRule from "./sys-user-rule";
import sysUserRole from "./sys-user-role";
import watcher from "./watcher";
import sysFile from "./sys-file";
import sysDict from "./sys-dict";
import sysSetting from "./sys-setting";
import sysMail from "./sys-mail";
import sysStorage from "./sys-storage";
import dashboard from "./dashboard";
import layout from "./layout";
import login from "./login";
import xinForm from "./xin-form";
import xinCrud from "./xin-crud";
import systemInfo from "./system-info";

export default {
  ...xinTable,
  ...xinCrud,
  ...menu,
  ...userSetting,
  ...sysUserList,
  ...sysUserDept,
  ...sysUserRule,
  ...sysUserRole,
  ...watcher,
  ...sysFile,
  ...sysDict,
  ...sysSetting,
  ...sysMail,
  ...sysStorage,
  ...dashboard,
  ...layout,
  ...login,
  ...xinForm,
  ...xinCrud,
  ...systemInfo,
};
