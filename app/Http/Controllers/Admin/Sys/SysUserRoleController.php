<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysRoleModel;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\DeleteMapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysRoleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 角色管理控制器
 */
#[RequestMapping('/admin/role', 'admin.role')]
#[Query, Create, Update]
class SysUserRoleController extends BaseController
{
    public function __construct(SysRoleRepository $repository, SysRoleModel $model)
    {
        $this->repository = $repository;
        $this->model = $model;
    }

    /** 删除角色 */
    #[DeleteMapping('/{id}', authorize: 'delete')]
    public function delete(int $id): JsonResponse
    {
        $model = $this->model->findOrFail($id);
        $count = $model->users()->count();
        if ($count > 0) {
            return $this->error('该角色下存在用户，无法删除');
        }
        $this->model->delete();
        return $this->success();
    }

    /** 设置角色权限 */
    #[PostMapping('/rule', 'update')]
    public function setRoleRule(Request $request): JsonResponse
    {
        $this->repository->setRule($request);
        return $this->success();
    }
}
