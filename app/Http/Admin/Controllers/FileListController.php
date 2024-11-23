<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\FileUpdateInfoRequest;
use App\Models\File\FileModel;
use App\Trait\BuilderTrait;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/file')]
class FileListController
{
    use BuilderTrait;

    #[Autowired]
    protected FileModel $model;

    #[GetMapping]
    #[Authorize('admin.file.list')]
    public function list(): JsonResponse {
        $searchField = [
            'group_id' => '=',
            'name' => 'like',
            'file_type' => '=',
        ];
        return $this->listResponse($this->model, $searchField);
    }

    #[PutMapping]
    #[Authorize('admin.file.edit')]
    public function edit(FileUpdateInfoRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }
}