<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Models\MonitorModel;
use App\Trait\BuilderTrait;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/monitor')]
class MonitorController
{
    use BuilderTrait;

    #[Autowired]
    protected MonitorModel $model;

    #[GetMapping]
    #[Authorize('admin.monitor.list')]
    public function list(): JsonResponse
    {
        $searchField = [
            'user_id' => '=',
            'name' => '=',
            'ip' => '=',
            'created_at' => 'date'
        ];
        return $this->listResponse($this->model, $searchField);
    }

}