<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysDictModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class SysDictRepository extends Repository
{
    /** @var array|string[] 验证规则 */
    protected array $validation = [
        'name' => 'required',
        'code' => 'required|unique:sys_dict,code',
        'describe' => 'sometimes',
        'type' => 'required|in:default,custom,system'
    ];

    /** @var array 验证消息 */
    protected array $messages = [
        'name.required' => '字典名称不能为空',
        'code.required' => '字典编码不能为空',
        'code.unique' => '字典编码已存在',
        'type.required' => '字典类型不能为空',
        'type.in' => '字典类型格式错误'
    ];

    /** @var array|string[] 搜索字段 */
    protected array $searchField = [
        'type' => '='
    ];

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['name', 'code', 'describe'];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysDictModel::newQuery();
    }
}