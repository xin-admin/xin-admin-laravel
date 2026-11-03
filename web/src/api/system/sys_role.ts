import createAxios from '@/utils/request';
import type ISysUser from "@/domain/iSysUser.ts";

export interface RuleFieldsList {
  key: string;
  title: string;
  local: string;
  children: RuleFieldsList[];
}

/** 获取角色用户列表 */
export async function users(id: number, params: {
  page: number;
  pageSize: number;
} = { page: 1, pageSize: 10 }) {
  return createAxios<API.ListResponse<ISysUser>>({
    url: '/system/role/users/' + id,
    method: 'get',
    params
  });
}

/** 设置启用状态 */
export async function statusRole(id: number) {
  return createAxios({
    url: '/system/role/status/' + id,
    method: 'put',
  });
}

/** 获取权限选项 */
export async function rulesList() {
  return createAxios<RuleFieldsList[]>({
    url: '/system/role/ruleList',
    method: 'get'
  });
}

/** 保存角色权限 */
export async function setRule(role_id: number, rule_ids: number[]) {
  return createAxios({
    url: '/system/role/setRule',
    method: 'post',
    data: {
      role_id,
      rule_ids
    }
  });
}
