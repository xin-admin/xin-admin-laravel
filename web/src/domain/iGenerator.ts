export interface IGenSettingType {
  name?: string;
  module?: string;
  path?: string;
  routePrefix?: string;
  abilitiesPrefix?: string;
  pagePath?: string;
  page_is_file?: boolean;
}

export interface IColumnsType {
  id: string;  // 字段的唯一标识
  name: string; // 字段名称
  title?: string; // 字段标题
  comment?: string; // 字段备注
  select?: boolean;
  form?: boolean;
  table?: boolean;
  dbColumns?: boolean;
  default?: string;
  length?: number;
  nullable?: boolean;
  type?: string;
  unsigned?: boolean;
  autoincrement?: boolean;
  precision?: number;
  presetValues?: string[];
  scale?: number;
}
