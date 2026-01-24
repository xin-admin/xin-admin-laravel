import createAxios from "@/utils/request";
import type { ISysFileInfo, SysFileType } from "@/domain/iSysFile";

export type FileListParams = {
  group_id?: number;
  page?: number;
  pageSize?: number;
}

/**
 * 获取文件列表
 * @param params 查询参数
 */
export function getFileList(params: FileListParams) {
  return createAxios<API.ListResponse<ISysFileInfo>>({
    url: '/sys/file/list',
    method: 'get',
    params,
  });
}

/**
 * 获取回收站文件列表
 * @param params 查询参数
 */
export function getTrashedFileList(params: {page?: number; pageSize?: number}) {
  return createAxios<API.ListResponse<ISysFileInfo>>({
    url: '/sys/file/list/trashed',
    method: 'get',
    params
  });
}

/**
 * 上传文件
 * @param file 文件
 * @param fileType 文件类型
 * @param groupId 文件组ID
 * @param onProgress 上传进度回调
 */
export function uploadFile(file: File, fileType: SysFileType, groupId?: number, onProgress?: (progress: number) => void) {
  const formData = new FormData();
  formData.append('file', file);
  if (groupId) {
    formData.append('group_id', groupId.toString());
    formData.append('file_type', fileType.toString());
  }
  return createAxios({
    timeout: 0,
    url: '/sys/file/list/upload',
    method: 'post',
    data: formData,
    headers: {
      'Content-Type': 'multipart/form-data',
    },
    onUploadProgress: (progressEvent) => {
      if (onProgress && progressEvent.total) {
        const progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
        onProgress(progress);
      }
    },
  });
}

/**
 * 删除文件（软删除）
 * @param id 文件ID
 */
export function deleteFile(id: number) {
  return createAxios({
    url: `/sys/file/list/${id}`,
    method: 'delete',
  });
}

/**
 * 批量删除文件（软删除）
 * @param ids 文件ID数组
 */
export function batchDeleteFiles(ids: number[]) {
  return createAxios<{count: number}>({
    url: '/sys/file/list/batch/delete',
    method: 'delete',
    data: { ids },
  });
}

/**
 * 彻底删除文件
 * @param id 文件ID
 */
export function forceDeleteFile(id: number) {
  return createAxios({
    url: `/sys/file/list/force-delete/${id}`,
    method: 'delete',
  });
}

/**
 * 批量彻底删除文件
 * @param ids 文件ID数组
 */
export function batchForceDeleteFiles(ids: number[]) {
  return createAxios<{count: number}>({
    url: '/sys/file/list/batch/force-delete',
    method: 'delete',
    data: { ids },
  });
}

/**
 * 恢复文件
 * @param id 
 * @returns 
 */
export function restoreFile(id: number) {
  return createAxios({
    url: `/sys/file/list/restore/${id}`,
    method: 'post',
  });
}

/**
 * 批量恢复文件
 * @param ids 文件 ID数组
 * @returns 
 */
export function batchRestoreFiles(ids: number[]) {
  return createAxios<{count: number}>({
    url: '/sys/file/list/batch/restore',
    method: 'post',
    data: { ids },
  });
}

/**
 * 复制文件
 * @param ids
 * @param groupId
 * @returns
 */
export function copyFile(ids: number | number[], groupId?: number) {
  return createAxios<ISysFileInfo>({
    url: `/sys/file/list/copy`,
    method: 'post',
    data: { group_id: groupId, ids },
  });
}

/**
 * 移动文件
 * @param ids
 * @param groupId
 * @returns
 */
export function moveFile(ids: number | number[], groupId?: number) {
  return createAxios<ISysFileInfo>({
    url: `/sys/file/list/move`,
    method: 'post',
    data: { group_id: groupId, ids },
  });
}

/**
 * 重命名文件
 * @param id 文件ID
 * @param name 新文件名
 * @returns 
 */
export function renameFile(id: number, name: string) {
  return createAxios({
    url: `/sys/file/list/rename/${id}`,
    method: 'put',
    data: { name },
  });
}

/**
 * 清空回收站文件
 * @returns 
 */
export function cleanTrashed() {
  return createAxios<{ count: number }>({
    url: '/sys/file/list/clean/trashed',
    method: 'delete',
  });
}