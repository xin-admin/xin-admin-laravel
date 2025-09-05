<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Repositories\Sys\SysDeptRepository;
use App\Services\SysUserDeptService;
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
