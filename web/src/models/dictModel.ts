// 全局共享数据示例
import { gitDict } from '@/services/system';
import { useRequest } from 'ahooks';
import { ReactNode } from 'react';

interface DictItem {
  label: string;
  value: string;
  type?: 'badge' | 'tag';
  status?: 'success' | 'error' | 'default' | 'processing' | 'warning';
}

interface DictDate {
  code: string;
  name: string
  type?: number
  dict_items: DictItem[]
}

const useDict = () => {

  /**
   * 格式化字典： request
   * @param data
   */
  const setDictJson = (data: DictDate[]): Map<string, DictItem[]> => {
    let dictMap: Map<string, DictItem[]> = new Map();
    data.forEach((dict: DictDate) => {
      dictMap.set(dict.code, dict.dict_items.map(a => Object.assign(a, { type: dict.type })));
    });
    return dictMap;
  }

  /**
   * 格式化字典： Enum
   * @param data
   */
  const setDictEnum = (data: DictDate[]): Map<string,{[key: string]: ReactNode}> => {
    let dictMap: Map<string, {[key: string]: ReactNode}> = new Map();
    data.forEach((dict: DictDate) => {
      let data: {[key: string]: ReactNode} = {};
      dict.dict_items.forEach((a) => {
        data[a.value] = a.label;
      })
      dictMap.set(dict.code, data);
    });
    return dictMap;
  }

  const { data: dictData, refresh, refreshAsync: refreshDictAsync } = useRequest(async () => {
    let token = localStorage.getItem('x-token');
    let data: DictDate[] = [];
    if (token) {
      if (localStorage.getItem('dictMap')) {
        console.log('-------获取字典缓存--------');
        data = JSON.parse(localStorage.getItem('dictMap')!)
      } else {
        let res = await gitDict();
        data = res.data
        localStorage.setItem('dictMap', JSON.stringify(data));
      }
    }
    return {
      dictJson: setDictJson(data),
      dictEnum: setDictEnum(data)
    };
  });

  /**
   * 刷新字典
   */
  const refreshDict = () => {
    localStorage.removeItem('dictMap');
    refresh();
  }

  /**
   * 通过键值获取字典
   * @param key
   */
  const getDictionaryData = async (key: string): Promise<DictItem[]> => {
    if (!dictData) {
      let res = await refreshDictAsync();
      return res.dictJson.has(key) ? res.dictJson.get(key)! : [];
    }
    return dictData.dictJson.has(key) ? dictData.dictJson.get(key)! : [];
  };

  return {
    refreshDict,
    getDictionaryData,
    dictEnum: dictData ? dictData.dictEnum : new Map()
  };
};

export default useDict;
