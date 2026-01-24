import type { algorithmType } from "@/layout/algorithm";

export type ThemeScheme = "light" | "dark" | "pink" | "green";

export type LayoutType = "side" | "top" | "mix" | "columns";

export interface ThemeProps {
  // 主题
  themeScheme?: ThemeScheme;
  // 背景
  background?: string;
  // 品牌色
  colorPrimary?: string;
  // 错误色
  colorError?: string;
  // 成功色
  colorSuccess?: string;
  // 警告色
  colorWarning?: string;
  // 基础组件的圆角大小
  borderRadius?: number;
  // 按钮和输入框等基础控件的高度
  controlHeight?: number;
  // 是否开启动画
  motion?: boolean;
  // 头部两侧内边距
  headerPadding?: number;
  // 头部高度
  headerHeight?: number;
  // 侧边栏宽度
  siderWeight?: number;
  // 内容区域内边距
  bodyPadding?: number;
  // 固定页脚
  fixedFooter?: boolean;
  // 基础文字颜色
  colorText?: string;
  // 基础背景颜色
  colorBg?: string;
  // 内容区域背景色
  bodyBg?: string;
  // 页脚背景色
  footerBg?: string;
  // 头部背景色
  headerBg?: string;
  // 头部文字颜色
  headerColor?: string;
  // 侧边栏背景色
  siderBg?: string;
  // 侧边栏文字颜色
  siderColor?: string;
  // 布局分割线边框颜色
  colorBorder?: string;
  // 开启布局边框
  layoutBorder?: boolean;
  // 算法
  algorithm?: algorithmType;
}
