import menu from "./menu";
import login from "./login";

import dashboardAnalysis from "./dashboard/analysis";
import dashboardMonitor from "./dashboard/monitor";
import dashboardWorkplace from "./dashboard/workplace";

import systemConfig from "./system/config";
import systemDept from "./system/dept";
import systemDict from "./system/dict";
import systemFile from "./system/file";
import systemUser from "./system/user";
import systemRole from "./system/role";
import systemRule from "./system/rule";

import aiChat from "./ai/chat";
import aiConversation from "./ai/conversation";
import aiAgent from "./ai/agent";

import userProfile from "./user/profile";

import xinForm from "./components/xin-form";
import xinTable from "./components/xin-table";
import xinCrud from "./components/xin-crud";
import notAuthorized from "./components/not-authorized.ts";
import layout from "./layout/layout";

export default {
  ...menu,
  ...login,
  ...dashboardAnalysis,
  ...dashboardMonitor,
  ...dashboardWorkplace,
  ...systemConfig,
  ...systemDept,
  ...systemDict,
  ...systemFile,
  ...systemUser,
  ...systemRole,
  ...systemRule,
  ...aiChat,
  ...aiConversation,
  ...aiAgent,
  ...userProfile,
  ...xinForm,
  ...xinTable,
  ...xinCrud,
  ...notAuthorized,
  ...layout,
};
