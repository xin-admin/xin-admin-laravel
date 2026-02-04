<?php

namespace App\Services;

use App\Support\Trait\Crud;
use App\Support\Trait\RequestJson;

abstract class BaseService
{
    use RequestJson, Crud;

    /**
     * 构建树
     * @param array $list
     * @param int $parentId
     * @return array
     */
    protected function getTreeData(array &$list, int $parentId = 0): array
    {
        $data = [];
        foreach ($list as $k => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->getTreeData($list, $item['id']);
                ! empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$k]);
            }
        }
        return $data;
    }
}
