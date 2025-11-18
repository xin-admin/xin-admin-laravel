<?php

namespace App\Services\Sys;

use App\Models\Sys\SysRuleModel;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;

class SysUserRuleService extends BaseService
{
    /**
     * 获取权限列表
     */
    public function getList(): JsonResponse
    {
        $rules = SysRuleModel::all();
        $data = $rules->toArray();
        $data = $this->getTreeData($data);
        return $this->success($data);
    }

    /**
     * 设置显示状态
     */
    public function setShow($ruleID): JsonResponse
    {
        $model = SysRuleModel::find($ruleID);
        if (! $model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->hidden = $model->hidden ? 0 : 1;
        $model->save();
        return $this->success();
    }

    /**
     * 设置状态
     */
    public function setStatus($ruleID): JsonResponse
    {
        $model = SysRuleModel::find($ruleID);
        if (! $model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->success();
    }

    /**
     * 获取父节点
     */
    public function getRuleParent(): JsonResponse
    {
        $data = SysRuleModel::query()
            ->whereIn('type', ['menu', 'route', 'nested-route'])
            ->get(['name', 'id', 'parent_id'])
            ->toArray();
        $data = $this->getTreeData($data);
        return $this->success($data);
    }

    /** 获取权限选择项 */
    public function getRuleFields(): JsonResponse
    {
        $data = SysRuleModel::query()
            ->where("status", 1)
            ->get(['name as title', 'parent_id', 'id as key', 'id', 'local'])
            ->toArray();
        $data = $this->getTreeData($data);
        return $this->success($data);
    }
}
