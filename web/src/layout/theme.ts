import type {ThemeProps} from "@/layout/typing.ts";

// 默认主题
export const defaultColorTheme: ThemeProps = {
  themeScheme: "light",
  background: "transparent",
  colorPrimary: "#1677ff",
  borderRadius: 8,
  colorText: "#000",
  colorBg: "#fff",
  bodyBg: "#f9f9f9",
  footerBg: "#fff",
  headerBg: "#fff",
  headerColor: "#000",
  siderBg: "#fff",
  siderColor: "#000",
  colorBorder: "#f0f0f0",
  algorithm: "defaultAlgorithm",
}

// 暗黑模式主题
export const darkColorTheme: ThemeProps = {
  themeScheme: "dark",
  background: "transparent",
  borderRadius: 8,
  colorPrimary: "#1677ff",
  colorText: "#fff",
  colorBg: "#000",
  bodyBg: "#000",
  footerBg: "#141414",
  headerBg: "#141414",
  headerColor: "#fff",
  siderBg: "#141414",
  siderColor: "#fff",
  colorBorder: "#282828",
  algorithm: "darkAlgorithm",
}

export const configTheme: ThemeProps = {
  // 主题
  themeScheme: "light",
  // 品牌色
  colorPrimary: "#1677ff",
  // 错误色
  colorError: "#ff4d4f",
  // 成功色
  colorSuccess: "#52c41a",
  // 警告色
  colorWarning: "#faad14",
  // 基础组件的圆角大小
  borderRadius: 10,
  // 按钮和输入框等基础控件的高度
  controlHeight: 32,
  // 头部两侧内边距
  headerPadding: 20,
  // 头部高度
  headerHeight: 56,
  // 侧边栏宽度
  siderWeight: 226,
  // 内容区域内边距
  bodyPadding: 20,
  // 固定页脚
  fixedFooter: false,
}
