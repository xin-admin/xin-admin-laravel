import {create, type StateCreator} from 'zustand';
import {createJSONStorage, devtools, persist} from 'zustand/middleware';
import type {DictData, DictMap, DictStore, DictStoreActions, DictStoreState} from './types';
import {getAllDict} from '@/api/system/sysDict';

const dictState: DictStoreState = {
  dictMap: {},
  dictData: []
};

const dictAction: StateCreator<DictStoreState, [], [], DictStoreActions> = (set, get) => ({
  initDict: async () => {
    try {
      const { data } = await getAllDict();
      const dictMap: DictMap = {};
      if (data.data) {
        data.data.forEach((dict) => {
          if (dict.code && dict.dict_items) {
            dictMap[dict.code] = dict;
          }
        });
      }
      set({
        dictMap: dictMap,
        dictData: data.data || []
      });
    } catch (error) {
      console.error('Failed to load dict data:', error);
    }
  },

  getDict: (code: string): DictData => {
    return get().dictMap[code] || [];
  },

  getOptions: (code: string): { label: string; value: string }[] => {
    const items = get().dictMap[code] || [];
    return items.dict_items.map((item) => ({
      label: item.label || '',
      value: item.value || '',
    }));
  },
});

const useDictStore = create<DictStore>()(
  devtools(
    persist(
      (...args) => ({
        ...dictState,
        ...dictAction(...args),
      }),
      {
        name: 'dict-storage',
        storage: createJSONStorage(() => localStorage),
      }
    ),
    {name: 'XinAdmin-Dict'}
  )
);

export type {DictStore, DictStoreState, DictStoreActions, DictMap};
export default useDictStore;
