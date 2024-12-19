import { request } from '@umijs/max';

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
