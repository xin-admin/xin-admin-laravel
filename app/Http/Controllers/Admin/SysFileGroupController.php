<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysFileGroupService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Delete;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\GetRoute;

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
