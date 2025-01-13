<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\FileUpdateInfoRequest;
use App\Http\BaseController;
use App\Models\FileModel;
use Illuminate\Http\JsonResponse;

/**
 * 文件列表
 */
#[AdminController]
#[RequestMapping('/system/file')]
class FileListController extends BaseController
{
    public function __construct()
    {
        $this->model = new FileModel;
        $this->searchField = [
            'group_id' => '=',
            'name' => 'like',
            'file_type' => '=',
        ];
    }

    /** 获取文件列表 */
    #[GetMapping] #[Authorize('system.file.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 修改文件信息 */
    #[PutMapping] #[Authorize('system.file.edit')]
    public function edit(FileUpdateInfoRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    // TODO 删除文件 （待完成）
}
