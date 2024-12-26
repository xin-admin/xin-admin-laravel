<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Models\XinUserMoneyRecordModel;
use Illuminate\Http\JsonResponse;

/**
 * 余额记录列表
 */
#[AdminController]
#[RequestMapping('/user/money/record')]
class UserMoneyRecordController extends BaseController
{
    public function __construct()
    {
        $this->model = new XinUserMoneyRecordModel;
        $this->searchField = [
            'created_at' => 'date',
            'user_id' => '=',
        ];
    }

    /** 获取用户余额记录列表 */
    #[GetMapping] #[Authorize('user.moneyLog.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }
}
