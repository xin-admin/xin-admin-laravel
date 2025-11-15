<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysDeptModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SysDeptRepository extends BaseRepository
{

    /** @var array|string[] 搜索字段 */
    protected array $searchField = [
        'parent_id' => '=',
        'status' => '='
    ];

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['name', 'leader', 'phone'];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysDeptModel::query();
    }

    protected function rules(): array
    {
        if (! $this->isUpdate()) {
            return [
                'name' => 'required|unique:sys_dept,name',
                'code' => 'required|unique:sys_dept,code',
                'type' => 'required|integer|in:0,1,2',
                'parent_id' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        if ($value != 0 && !DB::table('sys_dept')->where('id', $value)->exists()) {
                            $fail('选择的上级部门不存在。');
                        }
                    },
                ],
                'sort' => 'required|integer',
                'phone' => 'nullable',
                'address' => 'nullable',
                'email' => 'nullable|email',
                'status' => 'required|in:0,1',
                'remark' => 'nullable',
            ];
        } else {
            $id = request()->route('id');
            return [
                'name' => [
                    'required',
                    Rule::unique('sys_dept', 'name')->ignore($id)
                ],
                'code' => [
                    'required',
                    Rule::unique('sys_dept', 'code')->ignore($id)
                ],
                'type' => 'required|integer|in:0,1,2',
                'sort' => 'required|integer',
                'phone' => 'nullable',
                'address' => 'nullable',
                'email' => 'nullable|email',
                'status' => 'required|in:0,1',
                'remark' => 'nullable',
            ];
        }
    }

    protected function messages(): array
    {
        return [
            'name.required' => '部门名称不能为空',
            'name.unique' => '部门名称已存在',
            'code.required' => '部门编码不能为空',
            'code.unique' => '部门编码已存在',
            'type.required' => '部门类型不能为空',
            'type.integer' => '部门类型必须是整数',
            'type.in' => '部门类型错误',
            'parent_id.required' => '上级部门不能为空',
            'parent_id.integer' => '上级部门ID必须是整数',
            'parent_id.exists' => '选择的上级部门不存在',
            'sort.required' => '排序字段不能为空',
            'sort.integer' => '排序字段必须是整数',
            'email.email' => '请输入有效的邮箱地址',
            'status.required' => '状态不能为空',
            'status.in' => '状态类型错误',
        ];
    }
}