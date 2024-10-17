<?php

namespace App\Http\Controllers\Admin\System;

use App\Attribute\Auth;
use App\Http\Controllers\Admin\Controller;
use App\Models\Setting\SettingGroupModel;
use App\Models\Setting\SettingModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    protected string $model = SettingModel::class;

    protected string $authName = 'system.setting';

    protected array $searchField = [
        'group_id' => '=',
    ];

    protected array $rule = [
        'group_id' => 'required',
        'key' => 'required',
        'title' => 'required',
        'type' => 'required',
    ];

    /**
     * 基础控制器查询方法
     */
    #[Auth('list')]
    public function list(): JsonResponse
    {
        $group_id = request()->query('group_id');
        if (! $group_id) {
            return $this->warn('请选择设置分组');
        }
        $list = $this->model::query()
            ->where('group_id', '=', $group_id)
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        return $this->success($list);
    }

    /**
     * 保存设置
     */
    #[Auth('add')]
    public function saveSetting(): JsonResponse
    {
        $data = request()->all();
        if (! isset($data['group_id'])) {
            return $this->warn('请选择分组');
        }
        foreach ($data as $key => $value) {
            $setting = $this->model::query()
                ->where('group_id', $data['group_id'])
                ->where('key', $key)
                ->first();
            if (!$setting) {
                continue;
            }
            $setting->values = $value;
            $setting->save();
        }
        return $this->success('保存成功！');
    }

    /**
     * 新增分组
     */
    #[Auth('addGroup')]
    public function addGroup(): JsonResponse
    {
        $data = request()->all();
        $validator = Validator::make($data, [], []);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        SettingGroupModel::query()->create($data);
        return $this->success('ok');
    }

    /**
     * 查询设置分组
     */
    #[Auth('querySettingGroup')]
    public function querySettingGroup(): JsonResponse
    {
        $rootGroup = SettingGroupModel::query()
            ->select(['id','key', 'title as label'])
            ->get()
            ->toArray();
        return $this->success($rootGroup);
    }
}
