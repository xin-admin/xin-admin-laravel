import createAxios from '@/utils/request';
import type ISysUser from "@/domain/iSysUser.ts";

export interface RuleFieldsList {
  key: string;
  title: string;
  local: string;
  children: RuleFieldsList[];
}

/** 设置启用状态 */
export async function statusRole(id: number) {
  return createAxios({
    url: '/sys-user/role/status/' + id,
    method: 'put',
  });
}

/** 获取用户列表 */
export async function users(id: number, params: {
  page: number;
  pageSize: number;
} = { page: 1, pageSize: 10 }) {
  return createAxios<API.ListResponse<ISysUser>>({
    url: '/sys-user/role/users/' + id,
    method: 'get',
    params
  });
}

/** 获取权限选项 */
export async function rulesList() {
  return createAxios<RuleFieldsList[]>({
    url: '/sys-user/role/rule/list',
    method: 'get'
  });
}

/** 保存角色权限 */
export async function saveRoleRules(role_id: number, rule_ids: number[]) {
  return createAxios({
    url: '/sys-user/role/rule',
    method: 'post',
    data: {
      role_id,
      rule_ids
    }
  });
}
