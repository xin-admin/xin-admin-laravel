<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Repositories\Sys\SysUserRepository;
use App\Services\SysUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 管理员列表控制器
 */
#[RequestMapping('/admin/list', 'admin.list')]
#[Create, Update, Delete, Query]
class SysUserListController extends BaseController
{
    public function __construct(SysUserRepository $repository, SysUserService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /** 重置管理员密码 */
    #[PutMapping('/resetPassword', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        return $this->service->resetPassword($request);
    }
}
