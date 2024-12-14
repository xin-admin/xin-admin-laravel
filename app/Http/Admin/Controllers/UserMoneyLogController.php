<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Modelss\User\UserMoneyLogModel;
use Illuminate\Http\JsonResponse;

/**
 * 余额记录列表
 */
#[AdminController]
#[RequestMapping('/user/money/log')]
class UserMoneyLogController extends BaseController
{
    #[Autowired]
    protected UserMoneyLogModel $model;

    // 查询字段
    protected array $searchField = [
        'created_at' => 'date',
        'user_id' => '=',
    ];

    /**
     * 获取用户余额记录列表
     */
    #[GetMapping]
    #[Authorize('user.moneyLog.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse($this->model);
    }
}
