<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysFileGroupModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class SysFileGroupRepository extends Repository
{
    protected array $validation = [
        'name' => 'required|string|max:255',
        'describe' => 'sometimes|string|max:500',
        'sort' => 'sometimes|integer|min:0',
    ];

    protected array $messages = [
        'name.required' => '分组名称不能为空',
        'name.string' => '分组名称必须是字符串',
        'name.max' => '分组名称不能超过50个字符',

        'sort.integer' => '分组排序必须是整数',
        'sort.min' => '分组排序不能为负数',

        'describe.string' => '分组描述必须是字符串',
        'describe.max' => '分组描述不能超过500个字符',
    ];

    protected array $searchField = ['name' => 'like'];

    protected function model(): Builder
    {
        return SysFileGroupModel::query();
    }
}