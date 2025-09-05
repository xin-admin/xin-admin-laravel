<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Repositories\Sys\SysLoginRecordRepository;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 管理员登录日志
 */
#[Query]
#[RequestMapping('/admin/login_record', 'admin.login_record')]
class SysLoginRecordController extends BaseController
{
    public function __construct(SysLoginRecordRepository $repository)
    {
        $this->repository = $repository;
    }

    /** 获取管理员登录日志 */
    #[GetMapping('/my')]
    public function get(): JsonResponse
    {
        $id = auth()->id();
        $data = $this->repository->getRecordByID($id);
        return $this->success($data);
    }
}