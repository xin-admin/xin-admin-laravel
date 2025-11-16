<?php

namespace App\Services\Sys;

use App\Models\Sys\SysFileGroupModel;
use App\Services\BaseService;

class SysFileGroupService extends BaseService
{
    /**
     * 获取文件分组列表
     */
    public function list(): array
    {
        $group = SysFileGroupModel::query()->get()->toArray();
        return $this->getTreeData($group);
    }
}
