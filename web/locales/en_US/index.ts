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
import dashboard from "./dashboard";
import layout from "./layout";
import login from "./login";
import xinForm from "./xin-form";
import xinCrud from "./xin-crud";
import xinTableV2 from "./xin-table-v2";

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
  ...dashboard,
  ...layout,
  ...login,
  ...xinForm,
  ...xinCrud,
  ...xinTableV2,
};