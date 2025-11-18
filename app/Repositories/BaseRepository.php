<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * 验证规则
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * 验证 messages
     * @return array
     */
    protected function messages(): array
    {
        return [];
    }

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
        $validator = Validator::make($data, $this->rules(), $this->messages())->stopOnFirstFailure();
        if ($validator->fails()) {
            throw new RepositoryException('Validation failed: ' . $validator->errors()->first());
        }
        try {
            return $validator->validated();
        } catch (ValidationException $e) {
            throw new RepositoryException("Validation failed: {$e->getMessage()}");
        }
    }

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

    /**
     * 查询操作
     * @param int $id
     * @return array
     */
    public function find(int $id): array
    {
        $model = $this->model()->find($id);
        if (empty($model)) {
            throw new RepositoryException("Model not found");
        }
        return $model->toArray();
    }

    /**
     * 列表查询操作
     * @param array $params
     * @return array
     */
    public function list(array $params): array
    {
        $pageSize = $params['pageSize'] ?? 10;
        $query = $this->model();
        return $this->buildSearch($params, $query)
            ->paginate($pageSize)
            ->toArray();
    }

    /**
     * 新增操作
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $data = $this->validation($data);
        if(empty($data)) {
            throw new RepositoryException('Validation failed: empty data');
        }
        $model = $this->model()->create($data);
        return !!$model;
    }

    /**
     * 更新操作
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $validated = $this->validation($data);
        $model = $this->model()->find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        return $model->update($validated);
    }

    /**
     * 删除操作
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $model = $this->model()->find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        return $model->delete();
    }

    /**
     * 是否为更新请求
     * @return bool
     */
    protected function isUpdate(): bool
    {
        return request()->method() == 'PUT' && request()->route('id');
    }
}
