<?php

namespace App\Models\User;

use App\Models\BaseModel;

/**
 * 用户权限模型
 */
class UserRuleModel extends BaseModel
{
    protected $table = 'user_rule';

    protected $fillable = ['pid', 'type', 'sort', 'name', 'path', 'icon', 'key', 'locale', 'status', 'show'];

    /**
     * 获取权限树
     */
    public function getRuleTree(): array
    {
        $data = self::query()
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();

        return getTreeData($data);
    }

    /**
     * 获取父级权限树
     */
    public function getRulePid(): array
    {
        $data = self::query()
            ->where('type', '<>', '2')
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();

        return getTreeData($data);
    }
}
