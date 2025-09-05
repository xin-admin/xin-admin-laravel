<?php
namespace App\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

abstract class Repository
{
    /**
     * 验证规则
     * @var array
     */
    protected array $validation = [];

    /**
     * 验证 messages
     * @var array
     */
    protected array $messages = [];

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

    /** 获取模型 */
    abstract protected function model(): Builder;

    /** 验证数据 */
    protected function validation(array $data): array
    {
        if (empty($this->validation)) {
            return [];
        }
        $validator = Validator::make($data, $this->validation, $this->messages)->stopOnFirstFailure();
        if ($validator->fails()) {
            throw new RepositoryException(
                'Validation failed: ' . $validator->errors()->first(),
            );
        }
        return $validator->validated();
    }

    /** 构建查询方法 */
    protected function buildSearch(array $params, Builder $model): Builder
    {
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

    public function find(int $id): Model
    {
        return $this->model()->find($id);
    }

    public function list(array $params): array
    {
        $pageSize = $params['pageSize'] ?? 10;
        $query = $this->model();
        return $this->buildSearch($params, $query)
            ->paginate($pageSize)
            ->toArray();
    }

    public function create(array $data): Model
    {
        $data = $this->validation($data);
        if(empty($data)) {
            throw new RepositoryException('Validation failed: empty data');
        }
        return $this->model()->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $validated = $this->validation($data);
        $model = $this->model()->find($id);
        if (empty($data)) {
            throw new RepositoryException('Model not found');
        }
        return $model->update($validated);
    }

    public function delete(int $id): bool
    {
        $model = $this->model()->find($id);
        if (empty($data)) {
            throw new RepositoryException('Model not found');
        }
        return $model->delete();
    }
}
