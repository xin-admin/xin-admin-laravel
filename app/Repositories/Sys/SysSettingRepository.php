<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysSettingModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class SysSettingRepository extends Repository
{
    protected array $validation = [
        'title' => 'required',
        'key' => 'required|min:2|max:255|unique:setting,key,group_id',
        'group_id' => 'required|exists:setting_group,id',
        'type' => 'required',
        'describe' => 'sometimes|string',
        'options' => [
            'sometimes',
            'regex:/^(?:[^=\n]+=[^=\n]+)(?:\n[^=\n]+=[^=\n]+)*$/',
        ],
        'props' => [
            'sometimes',
            'regex:/^(?:[^=\n]+=[^=\n]+)(?:\n[^=\n]+=[^=\n]+)*$/',
        ],
        'sort' => 'sometimes|integer',
        'values' => 'sometimes|string',
    ];

    protected array $searchField = [ 'group_id' => '=' ];

    protected array $messages = [
        'title.required' => '标题字段是必填的',
        'key.required' => '键名字段是必填的',
        'key.min' => '键名至少需要 :min 个字符',
        'key.max' => '键名不能超过 :max 个字符',
        'key.unique' => '该键名在此分组中已存在',
        'group_id.required' => '分组ID是必填的',
        'group_id.exists' => '选择的分组不存在',
        'type.required' => '类型字段是必填的',
        'describe.string' => '描述必须是字符串',
        'options.regex' => '选项格式不正确，应为 key=value 格式，多个用换行分隔',
        'props.regex' => '属性格式不正确，应为 key=value 格式，多个用换行分隔',
        'sort.integer' => '排序必须是整数',
        'values.string' => '值必须是字符串',
    ];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysSettingModel::query();
    }
}