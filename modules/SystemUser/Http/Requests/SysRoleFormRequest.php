<?php

namespace Modules\SystemUser\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Common\Http\Requests\BaseFormRequest;

class SysRoleFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        if (!$this->isUpdate()) {
            return [
                'name' => 'required|unique:sys_role,name',
                'sort' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'status' => 'required|integer|in:0,1',
            ];
        } else {
            $id = $this->route('id');
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('sys_role', 'name')->ignore($id),
                ],
                'sort' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'status' => 'required|integer|in:0,1',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => '角色名称不能为空',
            'name.unique' => '角色名称已存在',
            'sort.required' => '排序不能为空',
            'sort.integer' => '排序必须为整数',
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误',
        ];
    }
}
