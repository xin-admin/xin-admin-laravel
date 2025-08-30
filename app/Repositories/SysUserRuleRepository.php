<?php

namespace App\Repositories;

use App\Models\AdminRuleModel;

class SysUserRuleRepository extends BaseRepository
{

    /** @var string 模型 */
    protected string $model = AdminRuleModel::class;

    /**
     * 通过 key 获取权限
     * @param array $keys
     * @return array
     */
    public function getRuleByKeys(array $keys): array
    {
        return $this->model()
            ->where('show', '=', 1)
            ->where('status', '=', 1)
            ->whereIn('key', $keys)
            ->whereIn('type', [0, 1])
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
    }

}