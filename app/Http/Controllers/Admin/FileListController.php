<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\FileUpdateInfoRequest;
use App\Models\FileModel;
use App\Services\FileService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 文件列表
 */
#[RequestMapping('/file/list', 'file.list')]
#[Query, Update, Delete]
class FileListController extends BaseController
{
    protected string $model = FileModel::class;
    protected string $formRequest = FileUpdateInfoRequest::class;
    protected array $searchField = [
        'group_id' => '=',
        'name' => 'like',
        'file_type' => '=',
    ];
    protected array $noPermission = ['download'];

    // TODO 删除文件 （待完成） 删除本地文件数据！

    /** 下载文件 */
    #[GetMapping('/download/{id}')]
    public function download(int $id): StreamedResponse
    {
        $fileService = new FileService;

        return $fileService->download($id);
    }
}
