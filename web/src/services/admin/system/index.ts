/**
 * 系统服务接口
 */
import { request } from '@umijs/max';

const api = {
  gitDictApi: '/admin/system/dictItem/dictList', // 获取系统字典
  getSettingGroupApi: '/admin/system/setting/querySettingGroup', // 获取设置分组
  addGroupApi: '/admin/system/setting/addGroup', // 添加设置分组
  saveSettingApi: '/admin/system/setting/saveSetting', // 保存设置
}


/**
 * 获取系统字典
 */
export const gitDict = () => {
  return request<API.ResponseStructure<any>>(api.gitDictApi, {
    method: 'get',
  })
}

/**
 * 获取设置分组
 */
export const getSettingGroup = () => {
  return request<API.ResponseStructure<any>>(api.getSettingGroupApi, {
    method: 'get',
  })
}

/**
 * 添加设置分组
 * @param data
 */
export const addGroup = (data: {
  key: string,
  title: string,
  pid?: number
}) => {
  return request<API.ResponseStructure<any>>(api.addGroupApi, {
    method: 'post',
    data
  })
}

/**
 * 保存设置
 */
export const saveSetting = (data: any) => {
  return request<API.ResponseStructure<any>>(api.saveSettingApi, {
    method: 'post',
    data
  })
}
