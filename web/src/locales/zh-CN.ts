import menu from './zh-CN/menu';
import settingDrawer from './zh-CN/settingDrawer';
import dashboard from "./zh-CN/dashboard";
import ai from './zh-CN/ai';
import generator from './zh-CN/generator'
export default {
  'navBar.lang': '语言',
  'layout.user.link.help': '帮助',
  'layout.user.link.privacy': '隐私',
  'layout.user.link.terms': '条款',
  'app.copyright.produced': '蚂蚁集团体验技术部出品',
  'app.preview.down.block': '下载此页面到本地项目',
  'app.welcome.link.fetch-blocks': '获取全部区块',
  'app.welcome.link.block-list': '基于 block 开发，快速构建标准页面',
  'app.welcome': '欢迎进入 XinAdmin 企业级管理系统',
  ...menu,
  ...settingDrawer,
  ...dashboard,
  ...ai,
  ...generator,
};
