/** 规则类型 */
export type IRuleType = 'menu' | 'route' | 'nested-route' | 'rule';

/** 菜单路由规则数据类型 */
export interface ISysRule {
  /** 权限ID */
  id?: number;
  /** 上级ID，顶级菜单的 pid 为 0 */
  pid?: number;
  /** 权限类型，分为菜单、路由、权限 */
  type?: IRuleType;
  /** 权限的唯一标识 */
  key?: string;
  /** 排序 */
  order?: number;
  /** 名称 */
  name?: string;
  /** 菜单的路径，menu 的路径会被当作前缀路由 */
  path?: string;
  /** 路由组件的路径，当类型值为 route 时该配置有效 */
  elementPath?: string | null;
  /** 路由的图标 */
  icon?: string;
  /** 多语言 */
  local?: string;
  /** 是否隐藏 */
  hidden?: number;
  /** 是否启用 */
  status?: number;
  /** 是否外链 */
  link?: number;
}

/** 菜单类型 */
export interface IMenus extends ISysRule {
  /** 子菜单 */
  children?: IMenus[];
}