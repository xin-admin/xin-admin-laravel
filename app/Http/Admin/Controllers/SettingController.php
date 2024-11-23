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
use App\Models\Setting\SettingModel;
use App\Trait\BuilderTrait;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/setting')]
class SettingController
{
    use BuilderTrait, RequestJson;

    #[Autowired]
    protected SettingModel $model;

    #[GetMapping]
    #[Authorize('admin.setting.list')]
    public function list(): JsonResponse
    {
        $searchField = ['group_id' => '=' ];
        return $this->listResponse($this->model, $searchField);
    }

    #[GetMapping('/{id}')]
    #[Authorize('admin.setting.list')]
    public function get(string $id): JsonResponse {
        return $this->success($this->model->query()->where('id', $id)->first());
    }

    #[PostMapping]
    #[Authorize('admin.setting.add')]
    public function add(SettingRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[PutMapping]
    #[Authorize('admin.setting.edit')]
    public function edit(SettingRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('admin.setting.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }
}