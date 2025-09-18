<?php

namespace App\Services;

use App\Models\Sys\SysRuleModel;
use Illuminate\Http\JsonResponse;

class SysUserRuleService extends Service
{
    private SysRuleModel $model;

    public function __construct(SysRuleModel $model)
    {
        $this->model = $model;
    }

    /**
     * 获取权限列表
     */
    public function getList(): JsonResponse
    {
        $rules = $this->model->all();
        return $this->success($rules->toArray());
    }

    /**
     * 设置显示状态
     */
    public function setShow($ruleID): JsonResponse
    {
        $model = $this->model->find($ruleID);
        if (! $model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->show = $model->show ? 0 : 1;
        $model->save();
        return $this->success();
    }

    /**
     * 设置状态
     */
    public function setStatus($ruleID): JsonResponse
    {
        $model = $this->model->find($ruleID);
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
        $data = $this->model
            ->whereIn('type', [0, 1])
            ->get(['name', 'id', 'parent_id'])
            ->toArray();
        $data = $this->getTreeData($data, 'id');
        return $this->success(compact('data'));
    }
}
