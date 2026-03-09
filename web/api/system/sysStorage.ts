import createAxios from '@/utils/request';

/** 获取存储配置 */
export async function getStorageConfig() {
  return createAxios({
    url: '/system/storage/config',
    method: 'get',
  });
}

/** 保存存储配置 */
export async function saveStorageConfig(data: Record<string, any>) {
  return createAxios({
    url: '/system/storage/save',
    method: 'post',
    data,
  });
}

/** 测试存储连接 */
export async function testStorageConnection(disk: string) {
  return createAxios({
    url: '/system/storage/test',
    method: 'post',
    data: { disk },
  });
}
