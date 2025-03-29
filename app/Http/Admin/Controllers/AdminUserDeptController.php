<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\AdminRequest\AdminUserDeptRequest;
use App\Http\BaseController;
use App\Models\AdminDeptModel;
use App\Service\impl\AdminUserDeptService;
use Illuminate\Http\JsonResponse;

/**
 * 部门管理控制器
 */
#[RequestMapping('/admin/dept')]
class AdminUserDeptController extends BaseController
{
    public function __construct()
    {
        $this->model = new AdminDeptModel;
        $this->service = new AdminUserDeptService;
    }

    /** 部门列表 */
    #[GetMapping] #[Authorize('admin.dept.list')]
    public function list(): JsonResponse
    {
        return $this->service->list();
    }

    /** 新增部门 */
    #[PostMapping] #[Authorize('admin.dept.add')]
    public function add(AdminUserDeptRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 编辑部门 */
    #[PutMapping] #[Authorize('admin.dept.edit')]
    public function edit(AdminUserDeptRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除部门 */
    #[DeleteMapping] #[Authorize('admin.dept.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }

}
