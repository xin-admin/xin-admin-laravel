import createAxios from '@/utils/request';
import type {ISysDept} from "@/domain/iSysDept.ts";
import React from "react";
import type ISysUser from "@/domain/iSysUser.ts";

/** 获取部门列表 */
export async function queryDept() {
  return createAxios<ISysDept[]>({
    url: '/system/dept',
    method: 'get',
  });
}

/** 新增部门 */
export async function createDept(data: ISysDept) {
  return createAxios<ISysDept[]>({
    url: '/system/dept',
    method: 'post',
    data: data
  });
}

/** 编辑部门信息 */
export async function updateDept(id: number, data: ISysDept) {
  return createAxios<ISysDept[]>({
    url: '/system/dept/' + id,
    method: 'put',
    data: data,
  });
}

/** 批量删除部门 */
export async function deleteDept(ids: React.Key[]) {
  return createAxios({
    url: '/system/dept',
    method: 'delete',
    data: { ids },
  });
}

/** 批量删除部门 */
export async function deptUsers(id: number, params: {
  page: number;
  pageSize: number;
} = { page: 1, pageSize: 10 }) {
  return createAxios<API.ListResponse<ISysUser>>({
    url: '/system/dept/users/' + id,
    method: 'get',
    params: params
  });
}
