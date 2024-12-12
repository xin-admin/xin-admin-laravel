import { request } from '@umijs/max';
import React from "react";

const api = {
  getRulePidApi: '/admin/adminRule/getRulePid', // 获取权限Pid
  setGroupRuleApi: '/admin/adminGroup/setGroupRule', // 设置分组权限
}

/**
 * 获取权限父节点ID
 */
export async function getRulePid() {
  return request<API.ResponseStructure<any>>(api.getRulePidApi, {
    method: 'get'
  });
}

/**
 * 设置管理员分组权限
 * @param data
 */
export async function setGroupRule(data: {id:number, rule_ids: React.Key[]}) {
  return request<API.ResponseStructure<any>>(api.setGroupRuleApi, {
    method: 'post',
    data: data
  });
}
