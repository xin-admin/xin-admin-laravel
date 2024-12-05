<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\FileUpdateInfoRequest;
use App\Http\BaseController;
use App\Models\File\FileModel;
use Illuminate\Http\JsonResponse;

/**
 * 文件列表
 */
#[AdminController]
#[RequestMapping('/admin/file')]
class FileListController extends BaseController
{
    #[Autowired]
    protected FileModel $model;

    // 查询字段
    protected array $searchField = [
        'group_id' => '=',
        'name' => 'like',
        'file_type' => '=',
    ];

    /**
     * 获取文件列表
     */
    #[GetMapping]
    #[Authorize('admin.file.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse($this->model);
    }

    /**
     * 修改文件信息
     */
    #[PutMapping]
    #[Authorize('admin.file.edit')]
    public function edit(FileUpdateInfoRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }
}
