<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Repositories\Sys\SysFileRepository;
use App\Services\SysFileService;
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
class SysFileController extends BaseController
{
    public function __construct(SysFileRepository $repository) {
        $this->repository = $repository;
    }

    protected array $noPermission = ['download'];

    /** 下载文件 */
    #[GetMapping('/download/{id}')]
    public function download(int $id): StreamedResponse
    {
        $fileService = new SysFileService;
        return $fileService->download($id);
    }
}
