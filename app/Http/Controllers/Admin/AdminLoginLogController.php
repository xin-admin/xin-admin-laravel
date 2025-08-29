<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\AdminLoginLogModel;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 管理员登录日志
 */
#[RequestMapping('/admin/loginlog', 'admin.loginlog')]
#[Query]
class AdminLoginLogController extends BaseController
{
    protected string $model = AdminLoginLogModel::class;

    protected array $searchField = [
        'username' => 'like',
        'ip' => 'like',
        'status' => '=',
    ];

    #[GetMapping('/my')]
    public function get(): JsonResponse
    {
        $username = auth()->user()['username'];
        $data = $this->model()
            ->query()
            ->where('username', $username)
            ->limit(20)
            ->orderBy('log_id', 'desc')
            ->get()
            ->toArray();
        return $this->success($data);
    }
}