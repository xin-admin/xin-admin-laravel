<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\OnlineTableRequest;
use App\Http\Admin\Requests\OnlineTableTypeRequest;
use App\Http\BaseController;
use App\Models\OnlineTableModel;
use Illuminate\Http\JsonResponse;

/**
 * 在线开发表格
 */
#[AdminController]
#[RequestMapping('/admin/online/table')]
class OnlineTableController extends BaseController
{
    public function __construct()
    {
        $this->model = new OnlineTableModel;
        $this->searchField = [
            'id' => '=',
            'name' => 'like',
            'code' => '=',
            'type' => '=',
            'created_at' => 'date',
            'updated_at' => 'date',
        ];
    }

    /** 获取在线开发记录列表 */
    #[GetMapping] #[Authorize('admin.online.table.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 新增在线开发表格记录 */
    #[PostMapping] #[Authorize('admin.online.table.add')]
    public function add(OnlineTableRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 修改在线开发表格记录 */
    #[PutMapping] #[Authorize('admin.online.table.edit')]
    public function edit(OnlineTableRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除在线开发记录 */
    #[DeleteMapping] #[Authorize('admin.online.table.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }

    /** 生成 CRUD 文件 */
    #[PostMapping('/create')]
    public function create(OnlineTableTypeRequest $request): JsonResponse
    {
        // TODO 生成 CRUD 文件未完成
        $data = $request->validated();
        $crud = new OnlineTableService;
        $sql = $crud->online($data);

        return $this->error($sql);
    }
}
