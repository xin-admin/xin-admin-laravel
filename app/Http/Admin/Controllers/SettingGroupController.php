<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\SettingRequest;
use App\Http\BaseController;
use App\Models\SettingGroupModel;
use Illuminate\Http\JsonResponse;

#[AdminController]
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
    public function add(SettingRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 修改设置 */
    #[PutMapping] #[Authorize('system.setting.group.edit')]
    public function edit(SettingRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除设置 */
    #[DeleteMapping]
    #[Authorize('system.setting.group.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }
}
