<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Modelss\MonitorModel;
use Illuminate\Http\JsonResponse;

/**
 * 监控管理
 */
#[AdminController]
#[RequestMapping('/monitor')]
class MonitorController extends BaseController
{
    #[Autowired]
    protected MonitorModel $model;

    // 查询字段
    protected array $searchField = [
        'user_id' => '=',
        'name' => '=',
        'ip' => '=',
        'created_at' => 'date',
    ];

    /**
     * 获取监控数据列表
     */
    #[GetMapping]
    #[Authorize('admin.monitor.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse($this->model);
    }
}
