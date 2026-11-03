<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class SysDictFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        if (!$this->isUpdate()) {
            return [
                'name' => 'required|max:100',
                'code' => 'required|max:100|unique:sys_dict,code',
                'describe' => 'nullable|max:500',
                'status' => 'required|in:0,1',
                'sort' => 'nullable|integer|min:0',
            ];
        } else {
            $id = $this->route('id');
            return [
                'name' => 'required|max:100',
                'code' => [
                    'required',
                    'max:100',
                    Rule::unique('sys_dict', 'code')->ignore($id)
                ],
                'describe' => 'nullable|max:500',
                'status' => 'required|in:0,1',
                'sort' => 'nullable|integer|min:0',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => '字典名称不能为空',
            'name.max' => '字典名称不能超过100个字符',
            'code.required' => '字典编码不能为空',
            'code.max' => '字典编码不能超过100个字符',
            'code.unique' => '字典编码已存在',
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误',
            'sort.integer' => '排序必须为整数',
            'sort.min' => '排序不能小于0',
        ];
    }
}
