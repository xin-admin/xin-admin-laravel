<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\OnlineTableRequest;
use App\Http\Admin\Requests\OnlineTableTypeRequest;
use App\Models\OnlineTableModel;
use App\Trait\BuilderTrait;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/online/table')]
class OnlineTableController
{

    use BuilderTrait, RequestJson;


    #[Autowired]
    protected OnlineTableModel $model;

    #[GetMapping]
    #[Authorize('admin.online.table.list')]
    public function list(): JsonResponse
    {
        $searchField = [
            'id' => '=',
            'name' => 'like',
            'code' => '=',
            'type' => '=',
            'created_at' => 'date',
            'updated_at' => 'date',
        ];
        return $this->listResponse($this->model, $searchField);
    }

    #[PostMapping]
    #[Authorize('admin.online.table.add')]
    public function add(OnlineTableRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[PutMapping]
    #[Authorize('admin.online.table.edit')]
    public function edit(OnlineTableRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('admin.online.table.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }

    #[PostMapping('/create')]
    public function create(OnlineTableTypeRequest $request): JsonResponse {
        // TODO 生成 CRUD 文件未完成
        $data = $request->validated();
        $crud = new OnlineTableService();
        $sql = $crud->online($data);
        return $this->error($sql);
    }
}