<?php
namespace App\Models\Admin;

use App\Models\BaseModel;

/**
 * 管理员权限模型
 */
class AdminRuleModel extends BaseModel
{
    protected $table = 'admin_rule';

    protected $fillable = ['pid', 'type', 'sort', 'name', 'path', 'icon', 'key', 'locale', 'status', 'show'];

    /**
     * 获取权限树
     * @return array
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
     * @return array
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
