import { request } from '@umijs/max';

/**
 * 导入 Sql 字段
 */
export async function importSql(table: string) {
  return request<API.ResponseStructure<any>>('/system/gen/importSql/' + table, {
    method: 'get'
  });
}

/**
 * 获取所有的数据表
 */
export async function getTableList() {
  return request<API.ResponseStructure<any>>('/system/gen/getTableList', {
    method: 'get'
  });
}
