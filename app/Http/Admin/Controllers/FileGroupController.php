<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Models\FileGroupModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * 文件分组控制器
 */
#[AdminController]
#[RequestMapping('/admin/file/group')]
class FileGroupController extends BaseController
{
    public function __construct()
    {
        $this->model = new FileGroupModel;
    }

    /** 文件分组列表 */
    #[GetMapping] #[Authorize('admin.file.group.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 添加文件分组 */
    #[PostMapping] #[Authorize('admin.file.group.add')]
    public function add(FormRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 修改文件分组 */
    #[PutMapping] #[Authorize('admin.file.group.edit')]
    public function edit(FormRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }
}
