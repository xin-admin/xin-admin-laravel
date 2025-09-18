<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 前台用户列表
 */
#[RequestMapping('/user/list', 'user.list')]
#[Query, Update]
class UserController extends BaseController
{
    public function __construct(UserRepository $repository, UserService $service)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    /** 重置密码 */
    #[PutMapping('/resetPassword', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        return $this->service->resetPassword($request);
    }
}
