<?php

namespace App\Http\Admin\Controllers;

use App\Http\BaseController;
use App\Models\AdminRuleModel;
use App\Service\AdminUserRuleService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Update;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;
use App\Http\Admin\Requests\AdminRequest\AdminUserRuleRequest;

/**
 * 管理员权限控制器
 */
#[RequestMapping('/admin/rule', 'admin.rule')]
#[Create, Update, Delete]
class AdminUserRuleController extends BaseController
{
    protected string $model = AdminRuleModel::class;

    protected string $formRequest = AdminUserRuleRequest::class;

    /** 管理员权限列表 */
    #[GetMapping(authorize: 'query')]
    public function list(): JsonResponse
    {
        $service = new AdminUserRuleService;
        return $service->list();
    }

    /** 获取父级权限 */
    #[GetMapping('/parent', authorize: 'query')]
    public function getRulesParent(): JsonResponse
    {
        $service = new AdminUserRuleService;
        return $service->getRuleParent();
    }

    /** 设置显示 */
    #[PutMapping('/show/{ruleID}', authorize: 'update')]
    public function show($ruleID): JsonResponse
    {
        $service = new AdminUserRuleService;
        return $service->setShow($ruleID);
    }

    /** 设置状态 */
    #[PutMapping('/status/{ruleID}', authorize: 'update')]
    public function status($ruleID): JsonResponse
    {
        $service = new AdminUserRuleService;
        return $service->setStatus($ruleID);
    }
}
