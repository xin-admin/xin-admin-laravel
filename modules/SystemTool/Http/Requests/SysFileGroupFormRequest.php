<?php

namespace Modules\SystemTool\Http\Requests;

use Illuminate\Support\Facades\DB;
use Modules\Common\Http\Requests\BaseFormRequest;

class SysFileGroupFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        if (!$this->isUpdate()) {
            return [
                'parent_id' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        if ($value != 0 && !DB::table('sys_file_group')->where('id', $value)->exists()) {
                            $fail('选择的上级分组不存在。');
                        }
                    },
                ],
                'name' => 'required|string|max:255',
                'describe' => 'sometimes|string|max:500',
                'sort' => 'sometimes|integer|min:0',
            ];
        } else {
            return [
                'name' => 'required|string|max:255',
                'describe' => 'sometimes|string|max:500',
                'sort' => 'sometimes|integer|min:0',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => '分组名称不能为空',
            'name.string' => '分组名称必须是字符串',
            'name.max' => '分组名称不能超过50个字符',
            'sort.integer' => '分组排序必须是整数',
            'sort.min' => '分组排序不能为负数',
            'describe.string' => '分组描述必须是字符串',
            'describe.max' => '分组描述不能超过500个字符',
        ];
    }
}
