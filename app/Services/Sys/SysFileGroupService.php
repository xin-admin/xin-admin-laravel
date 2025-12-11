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
        $query = SysFileGroupModel::query()->orderBy('sort', 'asc');
        $keywordSearch = request()->input('keywordSearch', '');
        // 快速搜索
        if (isset($keywordSearch) && $keywordSearch != '') {
            $query->whereAny(
                ['name'],
                'like',
                '%'.str_replace('%', '\%', $keywordSearch).'%'
            );
            return $query->get()->toArray();
        }
        $group = $query->get()->toArray();
        return $this->getTreeData($group);;
    }
}
