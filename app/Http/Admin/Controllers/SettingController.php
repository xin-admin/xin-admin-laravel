<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\SettingRequest;
use App\Http\BaseController;
use App\Models\SettingModel;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/setting')]
class SettingController extends BaseController
{
    #[Autowired]
    protected SettingModel $model;

    // 查询字段
    protected array $searchField = ['group_id' => '='];

    /**
     * 获取设置列表
     */
    #[GetMapping]
    #[Authorize('admin.setting.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse($this->model);
    }

    /**
     * 通过ID获取设置列表
     */
    #[GetMapping('/{id}')]
    #[Authorize('admin.setting.list')]
    public function get(string $id): JsonResponse
    {
        $data = $this->model->query()->where('id', $id)->first()->toArray();

        return $this->success($data);
    }

    /**
     * 新增设置
     */
    #[PostMapping]
    #[Authorize('admin.setting.add')]
    public function add(SettingRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    /**
     * 修改设置
     */
    #[PutMapping]
    #[Authorize('admin.setting.edit')]
    public function edit(SettingRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    /**
     * 删除设置
     */
    #[DeleteMapping]
    #[Authorize('admin.setting.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse($this->model);
    }
}
