<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\Controller;
use App\Models\User\UserMoneyLogModel;

class UserMoneyLogController extends Controller
{
    protected string $model = UserMoneyLogModel::class;

    protected string $authName = 'user.moneyLog';

    protected array $searchField = [
        'created_at' => 'date',
        'user_id' => '=',
    ];
}
