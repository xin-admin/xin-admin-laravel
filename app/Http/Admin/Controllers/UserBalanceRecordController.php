<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Models\XinBalanceRecordModel;
use Illuminate\Http\JsonResponse;

/**
 * 余额记录列表
 */
#[AdminController]
#[RequestMapping('/user/balance/record')]
class UserBalanceRecordController extends BaseController
{
    public function __construct()
    {
        $this->model = new XinBalanceRecordModel;
        $this->searchField = [
            'created_at' => 'date',
            'user_id' => '=',
            'created_by' => '=',
        ];
    }

    /** 获取用户余额记录列表 */
    #[GetMapping] #[Authorize('user.balance.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }
}
