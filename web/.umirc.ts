import { defineConfig } from '@umijs/max';

export default defineConfig({
  hash: true,
  model: {},
  initialState: {},
  layout: {},
  locale: {},
  antd: {},
  request: {},
  headScripts: [
    // 解决首次加载时白屏的问题
    { src: '/scripts/loading.js', async: true },
  ],
  mfsu: {
    strategy: 'normal',
  },
  conventionRoutes: {
    exclude: [/\/components\//, /\/models\//],
  },
  esbuildMinifyIIFE: true,
  npmClient: 'pnpm',
  favicons: [ 'https://file.xinadmin.cn/file/favicons.ico' ],
  metas: [
    { name: 'keywords', content: 'Xin Admin,Umi,Umi js,中后台管理框架,React,ThinkPHP,xinadmin,admin,react admin,think admin' },
    { name: 'description', content: 'Xin Admin是一款基于 Ant Design Pro components 构建一套完善的 Xin Table， 只需一个 Columns 就可以实现增删改查等表单、表格、查询等功能，以及组件的高度自定义' },
  ],
  define: {
    "process.env.DOMAIN": process.env.DOMAIN,
  }
});
