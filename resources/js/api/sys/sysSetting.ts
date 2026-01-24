import createAxios from '@/utils/request';
import type { ISettingGroup } from '@/domain/iSettingGroup';
import type { ISetting } from '@/domain/iSetting';

/** 获取设置组列表 */
export async function getSettingGroupList() {
  return createAxios<ISettingGroup[]>({
    url: '/system/setting/group',
    method: 'get',
  });
}

/** 新增设置组 */
export async function createSettingGroup(data: ISettingGroup) {
  return createAxios({
    url: '/system/setting/group',
    method: 'post',
    data,
  });
}

/** 更新设置组 */
export async function updateSettingGroup(id: number, data: ISettingGroup) {
  return createAxios({
    url: `/system/setting/group/${id}`,
    method: 'put',
    data,
  });
}

/** 删除设置组 */
export async function deleteSettingGroup(id: number) {
  return createAxios({
    url: `/system/setting/group/${id}`,
    method: 'delete',
  });
}

/** 获取设置项列表 */
export async function getSettingItemList(group_id?: number) {
  return createAxios<ISetting[]>({
    url: '/system/setting/items',
    method: 'get',
    params: { group_id },
  });
}

/** 新增设置项 */
export async function createSettingItem(data: ISetting) {
  return createAxios<ISetting>({
    url: '/system/setting/items',
    method: 'post',
    data,
  });
}

/** 更新设置项 */
export async function updateSettingItem(id: number, data: ISetting) {
  return createAxios<ISetting>({
    url: `/system/setting/items/${id}`,
    method: 'put',
    data,
  });
}

/** 删除设置项 */
export async function deleteSettingItem(id: number) {
  return createAxios({
    url: `/system/setting/items/${id}`,
    method: 'delete',
  });
}

/** 保存设置项值 */
export async function saveSettingItemValue(id: number, values: string) {
  return createAxios({
    url: `/system/setting/items/save/${id}`,
    method: 'put',
    data: { values },
  });
}
