<?php

namespace App\Service;

use App\Trait\RequestJson;

abstract class BaseService
{
    use RequestJson;

    /**
     * 构建树
     */
    protected function getTreeData(&$list, int $parentId = 0): array
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->getTreeData($list, $item['rule_id']);
                ! empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }

        return $data;
    }
}
