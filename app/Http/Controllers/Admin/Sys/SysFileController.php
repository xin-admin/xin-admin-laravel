<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysFileRepository;
use App\Services\SysFileService;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
