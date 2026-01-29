<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\GetRoute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysFileGroupRepository;
use App\Services\Sys\SysFileGroupService;
use Illuminate\Http\JsonResponse;

/**
 * 文件分组控制器
 */
#[RequestAttribute('/sys/file/group', 'system.file.group')]
#[Create, Update, Delete]
class SysFileGroupController extends BaseController
{
    protected string $repository = SysFileGroupRepository::class;

    /**
     * 获取文件分组列表
     */
    #[GetRoute( authorize: "query")]
    public function list(SysFileGroupService $service): JsonResponse
    {
        return $this->success($service->list());
    }
}
