<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Models\File\FileGroupModel;
use App\Trait\BuilderTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/file/group')]
class FileGroupController
{

    use BuilderTrait;

    protected FileGroupModel $model;

    #[GetMapping]
    #[Authorize('admin.file.group.list')]
    public function list(): JsonResponse {
        return $this->listResponse($this->model);
    }

    #[PostMapping]
    #[Authorize('admin.file.group.add')]
    public function add(FormRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[PutMapping]
    #[Authorize('admin.file.group.edit')]
    public function edit(FormRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

}