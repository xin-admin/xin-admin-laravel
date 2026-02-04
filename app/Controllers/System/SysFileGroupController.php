<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Delete;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;
use App\Services\AnnoRoute\Route\GetRoute;
use App\Services\System\SysFileGroupService;
use Illuminate\Http\JsonResponse;

/**
 * 文件分组控制器
 */
#[RequestAttribute('/system/file/group', 'system.file.group')]
#[Create, Update, Delete]
class SysFileGroupController extends BaseController
{

    public function __construct(
        protected SysFileGroupService $service
    ) {}

    /**
     * 获取文件分组列表
     */
    #[GetRoute( authorize: "query")]
    public function list(): JsonResponse
    {
        $data = $this->service->list();
        return $this->success($data);
    }
}
