<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use Modules\Common\Trait\RequestJson;

abstract class BaseController extends Controller
{
    use RequestJson;

    /**
     * 查询字符串
     * The fields queried by the current model
     *
     * @var array
     */
    protected array $searchField = [];

    /**
     * 快速搜索字段
     * Quick search field
     *
     * @var array
     */
    protected array $quickSearchField = [];


    /** 构建查询方法 */
    protected function buildSearch(array $params, Builder $model): Builder
    {
        // 构建筛选
        if (isset($params['filter']) && $params['filter'] != '') {
            if(is_array($params['filter'])) {
                $filter = $params['filter'];
            } else {
                $filter = json_decode($params['filter'], true);
            }
            foreach ($filter as $k => $v) {
                if (! $v) {
                    continue;
                }
                $model->whereIn($k, $v);
            }
            unset($params['filter']);
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
            if(is_array($params['sorter'])) {
                $sorter = $params['sorter'];
            } else {
                $sorter = json_decode($params['sorter'], true);
            }
            if (count($sorter) > 0) {
                $column = array_keys($sorter)[0];
                $direction = $sorter[$column] == 'ascend' ? 'asc' : 'desc';
                $model->orderBy($column, $direction);
            }
        }

        return $model;
    }


}
