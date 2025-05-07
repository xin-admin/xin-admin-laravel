<?php

namespace App\Common\Trait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait BuildSearch
{

    /**
     * 构建查询方法
     */
    private function buildSearch(Request $request, Builder $model): Builder
    {
        $params = $request->all();
        // 构建筛选
        if (isset($params['filter']) && $params['filter'] != '') {
            $filter = json_decode($params['filter'], true);
            foreach ($filter as $k => $v) {
                if (! $v) {
                    continue;
                }
                $model->whereIn($k, $v);
            }
        }

        // 构建查询
        foreach ($this->searchField ?? [] as $key => $op) {
            if (isset($params[$key]) && $params[$key] != '') {
                if (in_array($op, ['=', '>', '<>', '<', '>=', '<='])) {
                    $model->where($key, $op, $params[$key]);

                    continue;
                }
                if ($op == 'like') {
                    $model->where($key, $op, '%'.$params[$key].'%');

                    continue;
                }
                if ($op == 'afterLike') {
                    $model->where($key, $op, $params[$key].'%');

                    continue;
                }
                if ($op == 'beforeLike') {
                    $model->where($key, $op, '%'.$params[$key]);

                    continue;
                }
                if ($op == 'date') {
                    $date = date('Y-m-d', strtotime($params[$key]));
                    $model->whereDate($key, $date);

                    continue;
                }
                if ($op == 'betweenDate') {
                    if (is_array($params[$key])) {
                        $start = $params[$key][0];
                        $end = $params[$key][1];
                        $model->whereDate($key, '>=', $start);
                        $model->whereDate($key, '<=', $end);
                    }
                }
            }
        }

        // 快速搜索
        if (isset($params['keywordSearch']) && $params['keywordSearch'] != '') {
            $quickSearchArr = $this->quickSearchField ?? [];
            if (count($quickSearchArr) > 0) {
                $model->whereAny(
                    $quickSearchArr,
                    'like',
                    '%'.str_replace('%', '\%', $params['keywordSearch']).'%'
                );
            }
        }

        // 构建排序
        if (isset($params['sorter']) && $params['sorter']) {
            $sorter = json_decode($params['sorter'], true);
            if (count($sorter) > 0) {
                $column = array_keys($sorter)[0];
                $direction = $sorter[$column] == 'ascend' ? 'asc' : 'desc';
                $model->orderBy($column, $direction);
            }
        }

        return $model;
    }
}