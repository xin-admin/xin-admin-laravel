<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\SystemRequest\FileUpdateInfoRequest;
use App\Http\BaseController;
use App\Models\FileModel;
use App\Service\FileService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 文件列表
 */
#[RequestMapping('/file/list')]
class FileListController extends BaseController
{
    protected array $noPermission = ['download'];

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
    #[GetMapping] #[Authorize('file.list.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 修改文件信息 */
    #[PutMapping] #[Authorize('file.list.edit')]
    public function edit(FileUpdateInfoRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除文件 */
    #[DeleteMapping] #[Authorize('file.list.delete')]
    public function delete(): JsonResponse
    {
        // TODO 删除文件 （待完成） 删除本地文件数据！
        return $this->deleteResponse();
    }

    /** 下载文件 */
    #[GetMapping('/download/{id}')]
    public function download(int $id): StreamedResponse
    {
        $fileService = new FileService;

        return $fileService->download($id);
    }
}
