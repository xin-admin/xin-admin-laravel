export interface SqlItemType {
  comment?: string;
  default?: string;
  length?: number;
  name?: string;
  notNull?: boolean;
  type?: string;
  unsigned?: boolean;
  autoincrement?: boolean;
  precision?: number;
  presetValues?: string[];
  scale?: number;
}

export interface IGenSettingType {
  name?: string;
  module?: string;
  path?: string;
  routePrefix?: string;
  abilitiesPrefix?: string;
  pagePath?: string;
  page_is_file?: boolean;
}
