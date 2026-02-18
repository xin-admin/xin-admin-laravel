import createAxios from "@/utils/request";
import type {IDict} from "@/domain/iDict";
import type {IDictItem} from "@/domain/iDictItem";

/**
 * 获取所有字典数据（带缓存）
 */
export function getAllDict() {
    return createAxios<(IDict & { dict_items: IDictItem[] })[]>({
        url: '/system/dict/list/all',
        method: 'get',
    });
}

/**
 * 刷新字典缓存
 */
export function refreshDictCache() {
    return createAxios({
        url: '/system/dict/list/refresh',
        method: 'post',
    });
}
