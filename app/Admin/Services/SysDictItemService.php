<?php

namespace App\Admin\Services;

use App\Common\Models\System\SysDictItemModel;
use App\Common\Services\BaseService;
use Illuminate\Validation\Rule;

class SysDictItemService extends BaseService
{
    protected SysDictItemModel $model;
    protected array $quickSearchField = ['label', 'value'];
    protected array $searchField = [
        'dict_id' => '=',
        'status' => '='
    ];

    protected function rules(): array
    {
        if($this->isUpdate()) {
            $id = request()->route('id');
            $dict_id = request('dict_id');
            return [
                'dict_id' => 'required|exists:sys_dict,id',
                'label' => 'required|max:100',
                'value' => [
                    'required',
                    'max:100',
                    Rule::unique('sys_dict_item')->where(function ($query) use ($dict_id) {
                        return $query->where('dict_id', $dict_id);
                    })->ignore($id)
                ],
                'color' => "nullable|string",
                'status' => 'required|in:0,1',
                'sort' => 'nullable|integer|min:0',
            ];
        } else {
            $dict_id = request('dict_id');
            return [
                'dict_id' => 'required|exists:sys_dict,id',
                'label' => 'required|max:100',
                'value' => [
                    'required',
                    'max:100',
                    Rule::unique('sys_dict_item')->where(function ($query) use ($dict_id) {
                        return $query->where('dict_id', $dict_id);
                    })
                ],
                'color' => "nullable|string",
                'status' => 'required|in:0,1',
                'sort' => 'nullable|integer|min:0',
            ];
        }
    }

    protected function messages(): array
    {
        return [
            'dict_id.required' => '字典ID不能为空',
            'dict_id.exists' => '字典不存在',
            'label.required' => '字典标签不能为空',
            'label.max' => '字典标签不能超过100个字符',
            'value.required' => '字典键值不能为空',
            'value.max' => '字典键值不能超过100个字符',
            'value.unique' => '该字典下已存在相同的键值',
            'color.in' => '颜色格式错误',
            'is_default.required' => '是否默认不能为空',
            'is_default.in' => '是否默认格式错误',
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误',
            'sort.integer' => '排序必须为整数',
            'sort.min' => '排序不能小于0',
        ];
    }
}
