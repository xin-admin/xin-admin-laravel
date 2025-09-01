<?php

namespace App\Repositories;

use App\Models\SysRuleModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SysRuleRepository extends BaseRepository
{
    protected array $validation = [
        'name' => 'required',
        'type' => 'required|in:1,2,3',
        'sort' => 'required|integer',
        'key' => 'required|unique:sys_rule,key',
        'path' => 'required',
        'status' => 'required|in:1,0',
        'show' => 'required|in:1,0',
        'parent_id' => 'required|integer|exists:sys_rule,id'
    ];

    protected array $messages = [
        'name.required' => '权限名称不能为空',
        'type.required' => '类型不能为空',
        'type.in' => '类型格式错误',
        'sort.required' => '排序不能为空',
        'sort.integer' => '排序必须为整数',
        'key.required' => '唯一标识不能为空',
        'key.unique' => '唯一标识已存在',
        'path.required' => '路径不能为空',
        'status.required' => '状态不能为空',
        'status.in' => '状态格式错误',
        'show.required' => '显示状态不能为空',
        'show.in' => '显示状态格式错误',
        'parent_id.required' => '父级权限不能为空',
        'parent_id.integer' => '父级权限格式错误',
        'parent_id.exists' => '父级权限不存在'
    ];

    protected array $searchField = [
        'type' => '=',
        'status' => '=',
        'show' => '=',
        'parent_id' => '='
    ];

    protected array $quickSearchField = ['name', 'key', 'path'];

    protected function model(): Builder
    {
        return SysRuleModel::newQuery();
    }

    /**
     * 通过 key 获取权限
     * @param array $keys
     * @return array
     */
    public function getRuleByKeys(array $keys): array
    {
        return $this->model()
            ->where('show', '=', 1)
            ->where('status', '=', 1)
            ->whereIn('key', $keys)
            ->whereIn('type', [0, 1])
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
    }
}