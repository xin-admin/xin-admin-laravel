<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\SystemRequest\SettingGroupRequest;
use App\Http\BaseController;
use App\Models\SettingGroupModel;
use App\Models\SettingModel;
use Illuminate\Http\JsonResponse;

/**
 * 设置分组控制器
 */
#[RequestMapping('/system/setting/group')]
class SettingGroupController extends BaseController
{
    public function __construct()
    {
        $this->model = new SettingGroupModel;
        $this->searchField = ['id' => '=', 'key' => '='];
    }

    /** 获取设置列表 */
    #[GetMapping] #[Authorize('system.setting.group.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 新增设置 */
    #[PostMapping] #[Authorize('system.setting.group.add')]
    public function add(SettingGroupRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 修改设置 */
    #[PutMapping] #[Authorize('system.setting.group.edit')]
    public function edit(SettingGroupRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除设置 */
    #[DeleteMapping]
    #[Authorize('system.setting.group.delete')]
    public function delete(): JsonResponse
    {
        $settingModel = new SettingModel;
        $data = request()->all();
        $delArr = explode(',', $data['id']);
        $setting = $settingModel->where('group_id', $delArr)->first();
        if ($setting) {
            return $this->error('请先删除该分组下的设置');
        }

        return $this->deleteResponse();
    }
}
