import createAxios from '@/utils/request';
import type {ISysRule} from "@/domain/iSysRule.ts";

/** 获取权限列表 */
export async function listRule() {
  return createAxios<ISysRule[]>({
    url: '/sys-user/rule',
    method: 'get',
  });
}

/** 设置显示状态 */
export async function ruleParent() {
  return createAxios<ISysRule[]>({
    url: '/sys-user/rule/parent',
    method: 'get',
  });
}

/** 设置显示状态 */
export async function showRule(id: number) {
  return createAxios({
    url: '/sys-user/rule/show/' + id,
    method: 'put',
  });
}

/** 设置启用状态 */
export async function statusRule(id: number) {
  return createAxios({
    url: '/sys-user/rule/status/' + id,
    method: 'put',
  });
}