<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysUserRepository;
use App\Services\SysUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    #[PutMapping('/reset/password', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        return $this->service->resetPassword($request);
    }

    /** 修改管理员状态 */
    #[PostMapping('/status/{id}', 'resetStatus')]
    public function resetStatus($id): JsonResponse
    {
        return $this->service->resetStatus($id);
    }
}
