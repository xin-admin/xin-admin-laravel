<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysRoleModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class SysRoleRepository extends Repository
{
    /** @var array|string[] 验证规则 */
    protected array $validation = [
        'name' => 'required|unique:sys_role,name',
        'sort' => 'required|integer',
        'rules' => 'required',
        'description' => 'required',
        'status' => 'required|in:0,1'
    ];

    /** @var array 验证消息 */
    protected array $messages = [
        'name.required' => '角色名称不能为空',
        'name.unique' => '角色名称已存在',
        'sort.required' => '排序不能为空',
        'sort.integer' => '排序必须为整数',
        'rules.required' => '权限不能为空',
        'description.required' => '描述不能为空',
        'status.required' => '状态不能为空',
        'status.in' => '状态格式错误'
    ];

    /** @var array|string[] 搜索字段 */
    protected array $searchField = [
        'status' => '='
    ];

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['name', 'description'];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysRoleModel::newQuery();
    }
}