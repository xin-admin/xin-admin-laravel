<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysFileGroupRepository;
use App\Services\Sys\SysFileGroupService;
use Illuminate\Http\JsonResponse;

/**
 * 文件分组控制器
 */
#[RequestMapping('/sys/file/group', 'sys.file.group')]
#[Create, Update, Delete]
class SysFileGroupController extends BaseController
{
    protected function repository(): RepositoryInterface
    {
        return app(SysFileGroupRepository::class);
    }

    /**
     * 获取文件分组列表
     */
    #[GetMapping( authorize: "query")]
    public function list(SysFileGroupService $service): JsonResponse
    {
        return $this->success($service->list());
    }
}
