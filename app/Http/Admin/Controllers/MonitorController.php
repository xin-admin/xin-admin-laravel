<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Models\MonitorModel;
use Illuminate\Http\JsonResponse;

/**
 * 监控管理
 */
#[AdminController]
#[RequestMapping('/monitor')]
class MonitorController extends BaseController
{
    public function __construct()
    {
        $this->model = new MonitorModel;
        $this->searchField = [
            'user_id' => '=',
            'name' => '=',
            'ip' => '=',
            'created_at' => 'date',
        ];
    }

    /** 获取监控数据列表 */
    #[GetMapping] #[Authorize('admin.monitor.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }
}
