<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysDeptModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class SysDeptRepository extends Repository
{
    /** @var array|string[] 验证规则 */
    protected array $validation = [
        'name' => 'required|unique:sys_dept,name',
        'sort' => 'required|integer',
        'parent_id' => 'required|integer|exists:sys_dept,id',
        'leader' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
        'status' => 'required|in:0,1'
    ];

    /** @var array 验证消息 */
    protected array $messages = [
        'name.required' => '部门名称不能为空',
        'name.unique' => '部门名称已存在',
        'sort.required' => '排序不能为空',
        'sort.integer' => '排序必须为整数',
        'parent_id.required' => '父级部门不能为空',
        'parent_id.integer' => '父级部门格式错误',
        'parent_id.exists' => '父级部门不存在',
        'leader.required' => '负责人不能为空',
        'phone.required' => '电话不能为空',
        'email.required' => '邮箱不能为空',
        'email.email' => '邮箱格式错误',
        'status.required' => '状态不能为空',
        'status.in' => '状态格式错误'
    ];

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
        return SysDeptModel::newQuery();
    }
}