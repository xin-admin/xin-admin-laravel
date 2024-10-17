<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\Controller;
use App\Models\MonitorModel;

class MonitorController extends Controller
{
    protected string $model = MonitorModel::class;

    protected string $authName = 'system.monitor';

    protected array $searchField = [
        'user_id' => '=',
        'name' => '=',
        'ip' => '=',
        'created_at' => 'date'
    ];
}
