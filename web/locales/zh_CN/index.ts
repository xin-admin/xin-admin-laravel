import menu from "./menu";
import login from "./login";

import dashboardAnalysis from "./dashboard/analysis";
import dashboardMonitor from "./dashboard/monitor";
import dashboardWorkplace from "./dashboard/workplace";

import systemInfo from "./system/info";
import systemConfig from "./system/config";
import systemDept from "./system/dept";
import systemDict from "./system/dict";
import systemFile from "./system/file";
import systemMail from "./system/mail";
import systemStorage from "./system/storage";
import systemUser from "./system/user";
import systemRole from "./system/role";
import systemRule from "./system/rule";
import systemAi from "./system/ai";

import aiChat from "./ai/chat";
import aiConversation from "./ai/conversation";
import aiAgent from "./ai/agent";

import userProfile from "./user/profile";

import xinForm from "./components/xin-form";
import xinTable from "./components/xin-table";
import xinCrud from "./components/xin-crud";

import layout from "./layout/layout";

export default {
  ...menu,
  ...login,
  ...dashboardAnalysis,
  ...dashboardMonitor,
  ...dashboardWorkplace,
  ...systemInfo,
  ...systemConfig,
  ...systemDept,
  ...systemDict,
  ...systemFile,
  ...systemMail,
  ...systemStorage,
  ...systemUser,
  ...systemRole,
  ...systemRule,
  ...systemAi,
  ...aiChat,
  ...aiConversation,
  ...aiAgent,
  ...userProfile,
  ...xinForm,
  ...xinTable,
  ...xinCrud,
  ...layout,
};
