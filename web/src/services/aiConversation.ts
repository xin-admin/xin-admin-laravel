import { request } from '@umijs/max';
import React from "react";

export async function save() {
  return request<API.ResponseStructure<any>>('', {
    method: 'post',
  });
}
