<?php

namespace App\Services;

use App\Support\Trait\RequestJson;

abstract class Service
{
    use RequestJson;

    /**
     * 构建树
     * @param array $list
     * @param string $key
     * @param int $parentId
     * @param array $fieldNames
     * @return array
     */
    protected function getTreeData(
        array &$list,
        string $key,
        int $parentId = 0,
        array $fieldNames = [
            'pid' => 'parent_id',
            'children' => 'children'
        ]
    ): array
    {
        $data = [];
        foreach ($list as $k => $item) {
            if ($item[$fieldNames['pid']] == $parentId) {
                $children = $this->getTreeData($list, $key, $item[$key]);
                ! empty($children) && $item[$fieldNames['children']] = $children;
                $data[] = $item;
                unset($list[$k]);
            }
        }
        return $data;
    }
}
