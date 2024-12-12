import { request } from '@umijs/max';

const api = {
  list: '/admin/file/file/list',
  add: '/admin/file/group/add',
  edit: '/admin/file/group/edit',
  delete: '/admin/file/file/delete',
}

export async function FileList(params: any) {
  return request<API.ResponseStructure<any>>(api.list, {
    method: 'get',
    params
  });
}

export async function DeleteFile(params: any) {
  return request<API.ResponseStructure<any>>(api.delete, {
    method: 'delete',
    params
  });
}

