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
use App\Http\Admin\Requests\DictItemRequest;
use App\Models\Dict\DictItemModel;
use App\Trait\BuilderTrait;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/dict/item')]
class DictItemController
{
    use BuilderTrait;

    #[Autowired]
    protected DictItemModel $model;

    #[GetMapping]
    #[Authorize('admin.dict.item.list')]
    public function list(): JsonResponse
    {
        $searchField = [
            'name' => 'like',
            'dict_id' => '='
        ];
        return $this->listResponse($this->model, $searchField);
    }

    #[PostMapping]
    #[Authorize('admin.dict.item.add')]
    public function add(DictItemRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[PutMapping]
    #[Authorize('admin.dict.item.edit')]
    public function edit(DictItemRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('admin.dict.item.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }

}