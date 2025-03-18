import { request } from '@umijs/max';

/**
 * 获取父级规则
 */
export async function getRuleParent() {
  return request<API.ResponseStructure<any>>(`/admin/rule/parent`, {
    method: 'get'
  });
}

/**
 * 切换显示状态
 */
export async function show(rule_id: any) {
  return request<API.ResponseStructure<any>>(`/admin/rule/show/${rule_id}`, {
    method: 'put'
  });
}

/**
 * 切换状态
 */
export async function status(rule_id: any) {
  return request<API.ResponseStructure<any>>(`/admin/rule/status/${rule_id}`, {
    method: 'put'
  });
}
