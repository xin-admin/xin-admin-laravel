<?php

namespace App\Service\impl;

use App\Models\AdminRuleModel;
use Illuminate\Http\JsonResponse;

class AdminUserRuleService extends BaseService
{
    /**
     * 获取权限树状列表
     */
    public function list(): JsonResponse
    {
        $model = new AdminRuleModel;
        $data = $model->orderBy('sort', 'asc')->get()->toArray();
        $data = $this->getTreeData($data, 'rule_id');

        return $this->success(compact('data'));
    }

    /**
     * 设置显示状态
     */
    public function setShow($ruleID): JsonResponse
    {
        $model = new AdminRuleModel;
        $data = $model->where('rule_id', $ruleID)->first();
        if (! $data) {
            return $this->error('数据不存在');
        }
        $model->where('rule_id', $ruleID)->update([
            'show' => $data->show ? 0 : 1,
        ]);

        return $this->success();
    }

    /**
     * 设置状态
     */
    public function setStatus($ruleID): JsonResponse
    {
        $model = new AdminRuleModel;
        $data = $model->where('rule_id', $ruleID)->first();
        if (! $data) {
            return $this->error('数据不存在');
        }
        $model->where('rule_id', $ruleID)->update([
            'status' => $data->status ? 0 : 1,
        ]);

        return $this->success();
    }

    /**
     * 获取父节点
     */
    public function getRuleParent(): JsonResponse
    {
        $model = new AdminRuleModel;
        $data = $model->whereIn('type', [0, 1])->get(['name', 'rule_id', 'parent_id'])->toArray();
        $data = $this->getTreeData($data, 'rule_id');

        return $this->success(compact('data'));
    }
}
