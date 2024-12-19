<?php

namespace App\Service;

use App\Attribute\Autowired;
use App\Models\AdminRuleModel;
use Illuminate\Http\JsonResponse;

class AdminUserRuleService extends BaseService
{
    #[Autowired]
    protected AdminRuleModel $model;

    /**
     * 获取权限树状列表
     */
    public function getDataTree(): JsonResponse
    {
        $data = $this->model->get()->toArray();
        $data = $this->getTreeData($data);

        return $this->success(compact('data'));
    }

    /**
     * 设置显示状态
     */
    public function setShow($ruleID): JsonResponse
    {
        $data = $this->model->where('rule_id', $ruleID)->first();
        if (! $data) {
            return $this->error('数据不存在');
        }
        $this->model->where('rule_id', $ruleID)->update([
            'show' => $data->show ? 0 : 1,
        ]);

        return $this->success();
    }

    /**
     * 设置状态
     */
    public function setStatus($ruleID): JsonResponse
    {
        $data = $this->model->where('rule_id', $ruleID)->first();
        if (! $data) {
            return $this->error('数据不存在');
        }
        $this->model->where('rule_id', $ruleID)->update([
            'status' => $data->status ? 0 : 1,
        ]);

        return $this->success();
    }

    /**
     * 获取父节点
     */
    public function getRuleParent(): JsonResponse
    {
        $data = $this->model->whereIn('type', [0, 1])->get()->toArray();
        $data = $this->getTreeData($data);

        return $this->success(compact('data'));
    }
}
