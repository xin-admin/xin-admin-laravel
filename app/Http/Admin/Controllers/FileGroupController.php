<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Modelss\File\FileGroupModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * 文件分组控制器
 */
#[AdminController]
#[RequestMapping('/admin/file/group')]
class FileGroupController extends BaseController
{
    #[Autowired]
    protected FileGroupModel $model;

    /**
     * 文件分组列表
     */
    #[GetMapping]
    #[Authorize('admin.file.group.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse($this->model);
    }

    /**
     * 添加文件分组
     */
    #[PostMapping]
    #[Authorize('admin.file.group.add')]
    public function add(FormRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    /**
     * 修改文件分组
     */
    #[PutMapping]
    #[Authorize('admin.file.group.edit')]
    public function edit(FormRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }
}
