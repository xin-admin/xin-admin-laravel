<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\SystemRequest\SettingRequest;
use App\Http\BaseController;
use App\Models\SettingModel;
use App\Service\SettingService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 系统设置
 */
#[RequestMapping('/system/setting')]
class SettingController extends BaseController
{
    public function __construct()
    {
        $this->model = new SettingModel;
        $this->searchField = ['group_id' => '='];
    }

    /** 获取设置列表 */
    #[GetMapping] #[Authorize('system.setting.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 通过ID获取设置列表 */
    #[GetMapping('/query/{id}')] #[Authorize('system.setting.list')]
    public function get(int $id): JsonResponse
    {
        $data = $this->model->query()->where('group_id', $id)->get()->toArray();

        return $this->success($data);
    }

    /** 新增设置 */
    #[PostMapping] #[Authorize('system.setting.add')]
    public function add(SettingRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 修改设置 */
    #[PutMapping] #[Authorize('system.setting.edit')]
    public function edit(SettingRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除设置 */
    #[DeleteMapping] #[Authorize('system.setting.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }

    #[PutMapping('/save/{id}')] #[Authorize('system.setting.save')]
    public function save(int $id): JsonResponse
    {
        $value = request()->all();
        foreach ($value as $k => $v) {
            $model = $this->model->where('key', $k)->where('group_id', $id)->first();
            if ($model) {
                $model->values = $v;
                $model->save();
            }
        }
        return $this->success('保存成功');
    }

    #[PostMapping('/refreshCache')] #[Authorize('system.setting.refresh')]
    public function refreshCache(): JsonResponse
    {
        SettingService::refreshSettings();
        return $this->success('重载成功');
    }
}
