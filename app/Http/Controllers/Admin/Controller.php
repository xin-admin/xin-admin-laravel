<?php

namespace App\Http\Controllers\Admin;

use App\Attribute\Auth;
use App\Http\BaseController;
use App\Models\BaseModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    /**
     * 权限名称
     */
    protected string $authName;

    /**
     * 当前控制器模型
     */
    protected string $model = BaseModel::class;

    /**
     * 普通查询字段
     */
    protected array $searchField = [];

    /**
     * 快速查询字段
     */
    protected array $quickSearchField = [];

    /**
     * 验证字段
     */
    protected array $rule = [];

    /**
     * 验证提示消息
     */
    protected array $message = [];

    /**
     * 构建查询方法
     */
    protected function buildSearch(): array
    {
        $params = request()->query();
        if (! class_exists($this->model)) {
            $this->throwError('当前控制器未设置模型!');
        }
        $model = $this->model::query();

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
        foreach ($this->searchField as $key => $op) {
            if (isset($params[$key]) && $params[$key] != '') {
                if (in_array($op, ['=', '>', '<>', '<', '>=', '<='])) {
                    $model->where($key, $op, $params[$key]);

                    continue;
                }
                if ($op == 'like') {
                    $model->where($key, $op, '%'.$params[$key].'%');

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
            $quickSearchArr = $this->quickSearchField;
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

        // 构建分页
        $paginate = [
            'prePage' => $params['pageSize'] ?? 10,
            'page' => $params['current'] ?? 1,
        ];
        trace($model->toSql());

        return [$model, $paginate];
    }

    /**
     * 基础控制器查询方法
     */
    #[Auth('list')]
    public function list(): JsonResponse
    {
        [$model, $paginate] = $this->buildSearch();
        $data = $model->paginate(
            $paginate['prePage'],
            page: $paginate['page'] ?? 1
        )->toArray();
        return $this->success($data);
    }

    /**
     * 基础控制器新增方法
     */
    #[Auth('add')]
    public function add(): JsonResponse
    {
        if (! class_exists($this->model)) {
            return $this->error('当前控制器未设置模型!');
        }
        $data = request()->all();
        $validator = Validator::make($data, $this->rule, $this->message);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $model = new $this->model;
        $model->create($data);

        return $this->success('ok');
    }

    /**
     * 基础控制器编辑方法
     */
    #[Auth('edit')]
    public function edit(): JsonResponse
    {
        if (! class_exists($this->model)) {
            return $this->error('当前控制器未设置模型!');
        }
        $data = request()->all();
        $model = new $this->model;
        $key = $model->getKeyName();
        $rule = ["$key" => 'required|integer'];
        // 只验证更新字段
        foreach ($this->rule as $k => $v) {
            if(isset($data[$k])) {
                $rule[$k] = $v;
            }
        }
        $validator = Validator::make($data, $rule, $this->message);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        try {
            $data = $validator->validated();
            (new $this->model)->where("$key", $data[$key])->update($data);
            return $this->success('ok');
        } catch (ValidationException $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 基础控制器删除方法
     */
    #[Auth('delete')]
    public function delete(): JsonResponse
    {
        if (! class_exists($this->model)) {
            return $this->error('当前控制器未设置模型!');
        }
        $data = request()->query();
        if (! isset($data['ids'])) {
            return $this->error('请选择ID');
        }
        $delArr = explode(',', $data['ids']);
        $delNum = (new $this->model)->destroy($delArr);
        if ($delNum != 0) {
            return $this->success('删除成功，删除了'.$delNum.'条数据');
        } else {
            return $this->warn('没有删除任何数据');
        }
    }
}
