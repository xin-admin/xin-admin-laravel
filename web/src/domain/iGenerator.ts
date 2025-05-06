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