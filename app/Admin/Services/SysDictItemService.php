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
        'dict_id' => '='
    ];

    protected function rules(): array
    {
        if($this->isUpdate()) {
            $id = request()->route('id');
            $dict_id = request('dict_id');
            return [
                'dict_id' => 'required|exists:sys_dict,id',
                'label' => 'required',
                'value' => [
                    'required',
                    Rule::unique('sys_dict_item')->where(function ($query) use ($dict_id) {
                        return $query->where('dict_id', $dict_id);
                    })->ignore($id)
                ],
                'switch' => 'required|int|in:0,1',
                'status' => 'required|in:default,success,error,processing,warning'
            ];
        } else {
            $dict_id = request('dict_id');
            return [
                'dict_id' => 'required|exists:sys_dict,id',
                'label' => 'required',
                'value' => [
                    'required',
                    Rule::unique('sys_dict_item')->where(function ($query) use ($dict_id) {
                        return $query->where('dict_id', $dict_id);
                    })
                ],
                'switch' => 'required|in:0,1',
                'status' => 'required|in:default,success,error,processing,warning'
            ];
        }

    }

    protected function messages(): array
    {
        return [
            'dict_id.required' => '字典ID不能为空',
            'dict_id.exists' => '字典不存在',
            'label.required' => '字典项名称不能为空',
            'value.required' => '字典项值不能为空',
            'switch.required' => '启用状态不能为空',
            'switch.in' => '启用状态格式错误',
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误'
        ];
    }
}