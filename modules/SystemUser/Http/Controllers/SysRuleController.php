<?php

namespace Modules\SystemUser\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\AnnoRoute\Attribute\DeleteRoute;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\PutRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\SystemUser\Http\Requests\SysRuleFormRequest;
use Modules\SystemUser\Models\SysRuleModel;

/**
 * 管理员权限控制器
 */
#[RequestAttribute('/system/rule', 'system.rule')]
class SysRuleController extends BaseController
{
    protected array $quickSearchField = ['name', 'key', 'path'];
    protected array $searchField = [
        'type' => '=',
        'status' => '=',
        'show' => '=',
        'parent_id' => '=',
    ];

    /** 获取权限列表（树形） */
    #[GetRoute(authorize: 'query')]
    public function query(): JsonResponse
    {
        $rules = SysRuleModel::all();
        $data = $rules->toArray();
        $data = getTreeData($data);
        return $this->success($data);
    }

    /** 创建权限 */
    #[PostRoute(authorize: 'create')]
    public function create(SysRuleFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysRuleModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑权限 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysRuleFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysRuleModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除权限 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        $model = SysRuleModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->delete();
        return $this->success();
    }

    /** 获取父级权限 */
    #[GetRoute('/parent', authorize: 'parentQuery')]
    public function getRulesParent(): JsonResponse
    {
        $data = SysRuleModel::query()
            ->whereIn('type', ['menu', 'route'])
            ->get(['name', 'id', 'parent_id'])
            ->toArray();
        $data = getTreeData($data);
        return $this->success($data);
    }

    /** 设置显示状态 */
    #[PutRoute('/show/{id}', authorize: 'show')]
    public function show(int $id): JsonResponse
    {
        $model = SysRuleModel::find($id);
        if (!$model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->hidden = $model->hidden ? 0 : 1;
        $model->save();
        return $this->success();
    }

    /** 设置启用状态 */
    #[PutRoute('/status/{id}', authorize: 'status')]
    public function status(int $id): JsonResponse
    {
        $model = SysRuleModel::find($id);
        if (!$model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->success();
    }
}
