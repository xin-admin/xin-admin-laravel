import { request } from '@umijs/max';

const api = {
  list: '/admin/file/group/list',
  add: '/admin/file/group/add',
  edit: '/admin/file/group/edit',
  delete: '/admin/file/group/delete',
}

export async function GroupList() {
  return request<API.ResponseStructure<any>>(api.list, {
    method: 'get'
  });
}

export async function AddGroup(data: any) {
  return request<API.ResponseStructure<any>>(api.add, {
    method: 'post',
    data
  });
}

export async function EditGroup(data: any) {
  return request<API.ResponseStructure<any>>(api.edit, {
    method: 'put',
    data
  });
}

export async function DeleteGroup(data: any) {
  return request<API.ResponseStructure<any>>(api.delete, {
    method: 'delete',
    data
  });
}
