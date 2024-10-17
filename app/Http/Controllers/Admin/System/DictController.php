<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\Controller;
use App\Models\Dict\DictModel;

class DictController extends Controller
{
    protected string $model = DictModel::class;

    protected string $authName = 'system.dict';

    protected array $searchField = [
        'id' => '=',
        'name' => 'like',
        'code' => '=',
        'type' => '=',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    protected array $rule = [
        'name' => 'required',
        'code' => 'required',
        'type' => 'required',
        'describe' => 'required'
    ];
}
