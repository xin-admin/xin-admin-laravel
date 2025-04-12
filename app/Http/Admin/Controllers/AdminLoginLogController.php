<?php

namespace App\Http\Admin\Controllers;

use App\Http\BaseController;
use App\Models\AdminLoginLogModel;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 管理员登录日志
 */
#[RequestMapping('/admin/loginlog')]
class AdminLoginLogController extends BaseController
{
    public function __construct()
    {
        $this->model = new AdminLoginLogModel();
        $this->searchField = [
            'username' => 'like',
            'ip' => 'like',
            'status' => '=',
        ];
    }

    #[GetMapping] #[Authorize('admin.loginlog.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    #[GetMapping('/my')]
    public function get(): JsonResponse
    {
        $username = auth()->user()['username'];
        $data = $this->model
            ->where('username', $username)
            ->limit(20)
            ->orderBy('log_id', 'desc')
            ->get()
            ->toArray();
        return $this->success($data);
    }
}