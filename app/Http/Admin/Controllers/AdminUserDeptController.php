<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\AdminRequest\AdminUserDeptRequest;
use App\Http\BaseController;
use App\Models\AdminDeptModel;
use App\Service\AdminUserDeptService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 部门管理控制器
 */
#[RequestMapping('/admin/dept', 'admin.dept')]
#[Create, Update, Delete]
class AdminUserDeptController extends BaseController
{
    protected string $model = AdminDeptModel::class;

    protected string $formRequest = AdminUserDeptRequest::class;

    /** 部门列表 */
    #[GetMapping(authorize: 'query')]
    public function list(): JsonResponse
    {
        $service = new AdminUserDeptService;
        return $service->list();
    }
}
