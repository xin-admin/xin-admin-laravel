<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\AdminRequest\AdminUserRoleRequest;
use App\Http\BaseController;
use App\Models\AdminRoleModel;
use App\Models\AdminRuleModel;
use App\Models\AdminUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\Find;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 角色管理控制器
 */
#[RequestMapping('/admin/role', 'admin.role')]
#[Find, Query, Create, Update]
class AdminUserRoleController extends BaseController
{
    protected string $model = AdminRoleModel::class;

    protected string $formRequest = AdminUserRoleRequest::class;

    /** 删除角色 */
    #[DeleteMapping(authorize: 'delete')]
    public function deleted(Request $request): JsonResponse
    {
        $user = AdminUserModel::whereIn('role_id', $request->all('role_ids'))->first();
        if (! $user) {
            return $this->error('该角色下存在用户，无法删除');
        }

        return $this->delete();
    }

    /** 设置角色权限 */
    #[PostMapping('/rule', 'update')]
    public function setRoleRule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:admin_role,role_id',
            'rule_keys' => 'required|array|exists:admin_rule,key',
        ]);
        $rule_ids = AdminRuleModel::whereIn('key', $validated)->pluck('rule_id')->toArray();
        $this->model
            ->where('role_id', $validated('role_id'))
            ->update([
                'rules' => implode(',', $rule_ids),
            ]);

        return $this->success();
    }
}
