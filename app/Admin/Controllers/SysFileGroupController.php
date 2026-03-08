<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysFileGroupService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
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
