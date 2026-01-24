/**
 * Stores 统一导出入口
 */

// ============== Stores ==============
export { useGlobalStore } from './global';

// ============== Selectors ==============
export * from './selectors';

// ============== Types ==============
export type {
  // 共享类型
  BreadcrumbItem,
  MenuIndexes,
  // Global Store 类型
  GlobalStore,
  GlobalState,
  GlobalAction,
  SiteState,
  SiteAction,
  LayoutState,
  LayoutAction,
  ThemeState,
  ThemeAction,
  // Auth Store 类型
  AuthStore,
  AuthState,
  AuthAction,
  UserState,
  UserAction,
  MenuState,
  MenuAction,
  AuthenticationState,
  AuthenticationAction,
} from './types';
