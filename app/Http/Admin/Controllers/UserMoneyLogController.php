<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Models\User\UserMoneyLogModel;
use App\Trait\BuilderTrait;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/user/moneyLog')]
class UserMoneyLogController
{
    use BuilderTrait;

    #[Autowired]
    protected UserMoneyLogModel $model;

    #[GetMapping]
    #[Authorize('user.moneyLog.list')]
    public function list(): JsonResponse
    {
        $searchField = [
            'created_at' => 'date',
            'user_id' => '=',
        ];
        return $this->listResponse($this->model, $searchField);
    }
}