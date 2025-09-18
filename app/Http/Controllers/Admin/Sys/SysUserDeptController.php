<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysDeptRepository;
use App\Services\SysUserDeptService;
use Illuminate\Http\JsonResponse;

/**
 * 部门管理控制器
 */
#[RequestMapping('/admin/dept', 'admin.dept')]
#[Create, Update, Delete]
class SysUserDeptController extends BaseController
{
    public function __construct(SysDeptRepository $repository, SysUserDeptService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /** 部门列表 */
    #[GetMapping(authorize: 'query')]
    public function listDept(): JsonResponse
    {
        return $this->service->list();
    }
}
