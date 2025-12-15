<?php

namespace App\Http\Requests\Sys;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class SysFileMoveOrCopyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('sys_file_group')->where('id', $value)->exists()) {
                        $fail('选择的上级部门不存在。');
                    }
                },
            ],
            'ids' => [
                'required',
                function ($attribute, $value, $fail) {
                    // 检查是否为数字
                    if (is_numeric($value)) {
                        return;
                    }
                    // 检查是否为数字数组
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (!is_numeric($item)) {
                                $fail("$attribute 中的元素必须全部是数字");
                                return;
                            }
                        }
                        return;
                    }
                    $fail("$attribute 必须是数字或数字数组");
                },
            ]
        ];
    }
}
