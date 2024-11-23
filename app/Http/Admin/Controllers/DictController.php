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
use App\Http\Admin\Requests\DictRequest;
use App\Models\Dict\DictModel;
use App\Trait\BuilderTrait;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/dict')]
class DictController
{
    use BuilderTrait;

    #[Autowired]
    protected DictModel $model;

    #[GetMapping]
    #[Authorize('admin.dict.list')]
    public function list(): JsonResponse
    {
        $searchField = [
            'id' => '=',
            'name' => 'like',
            'code' => '=',
            'type' => '=',
            'created_at' => 'date',
            'updated_at' => 'date',
        ];
        return $this->listResponse($this->model, $searchField);
    }

    #[PostMapping]
    #[Authorize('admin.dict.add')]
    public function add(DictRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[PutMapping]
    #[Authorize('admin.dict.edit')]
    public function edit(DictRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('admin.dict.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }
}