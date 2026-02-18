import type {IDictItem} from '@/domain/iDictItem';


/** 字典 Map */
export type DictMap = Record<string, IDictItem[]>;

/** 字典 Store 状态 */
export interface DictStoreState {
  /** 字典数据映射 */
  dictMap: DictMap;
}

/** 字典 Store 操作 */
export interface DictStoreActions {
  /** 初始化字典数据 */
  initDict: () => Promise<void>;
  /** 根据编码获取字典 */
  getDictItem: (code: string, value?: string | number) => IDictItem | null;
  /** 获取 Select 选项格式数据 */
  getOptions: (code: string) => { label: string; value: string }[];
}

export type DictStore = DictStoreState & DictStoreActions;
