<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\SystemRequest\FileGroupRequest;
use App\Http\BaseController;
use App\Models\FileGroupModel;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 文件分组控制器
 */
#[RequestMapping('/file/group')]
class FileGroupController extends BaseController
{
    public function __construct()
    {
        $this->model = new FileGroupModel;
        $this->searchField = ['name' => 'like'];
    }

    /** 文件分组列表 */
    #[GetMapping] #[Authorize('file.group.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 添加文件分组 */
    #[PostMapping] #[Authorize('file.group.add')]
    public function add(FileGroupRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 修改文件分组 */
    #[PutMapping] #[Authorize('file.group.edit')]
    public function edit(FileGroupRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除文件分组 */
    #[DeleteMapping] #[Authorize('file.group.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }
}
