<?php

namespace App\Http\Controllers;

use App\Support\Trait\BuildSearch;
use App\Support\Trait\RequestJson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: "
        XinAdmin [ A Full stack framework ] <br>
        Copyright (c) 2023~2024 http://xinadmin.cn All rights reserved. <br>
        Apache License ( http://www.apache.org/licenses/LICENSE-2.0 ) <br>
        Author: 小刘同学 <2302563948@qq.com> <br>
    ",
    title: 'XinAdmin DOCUMENTS',
)]
abstract class BaseController
{
    use RequestJson, BuildSearch;

    /**
     * 当前控制器中用于CRUD的模型类
     * The model class used for CRUD in the current controller
     *
     * @var string
     */
    protected string $model;

    /**
     * 当前控制器中用于新增或者编辑的表单验证
     * The form validation used for CRUD in the current controller
     *
     * @var string
     */
    protected string $formRequest;

    /**
     * 权限验证白名单
     * Permission verification whitelist
     *
     * @var array
     */
    protected array $noPermission = [];

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

    /**
     * 查询响应
     */
    public function find($id): JsonResponse
    {
        $model = $this->model();
        $key = $model->getKeyName();
        $data = $model->where($key, $id)->first()->toArray();

        return $this->success($data);
    }

    /** 列表响应 */
    public function query(Request $request): JsonResponse
    {
        $pageSize = $request->input('pageSize', 10);

        $query = $this->model()->query();
        $data = $this->buildSearch($request, $query)
            ->paginate($pageSize)
            ->toArray();

        return $this->success($data);
    }

    /**
     * 更新响应
     */
    public function update(): JsonResponse
    {
        $data = $this->formRequest()->validated();
        $model = $this->model();
        $key = $model->getKeyName();
        $model->where($key, $data[$key])->update($data);

        return $this->success('ok');
    }

    /**
     * 新增响应
     */
    public function create(): JsonResponse
    {
        $data = $this->formRequest()->validated();
        $this->model()->create($data);

        return $this->success('ok');
    }

    /**
     * 删除响应
     */
    public function delete(): JsonResponse
    {
        $data = request()->all();
        $model = $this->model();
        $key = $model->getKeyName();
        if (! isset($data[$key])) {
            return $this->error('请输入删除KEY！');
        }
        $delArr = explode(',', $data[$key]);
        $delNum = $model->destroy($delArr);
        if ($delNum != 0) {
            return $this->success('删除成功，删除了'.$delNum.'条数据');
        } else {
            return $this->warn('没有删除任何数据');
        }
    }


    /**
     * 获取当前控制器的模型
     * Obtain the model of the current controller
     *
     * @return Model
     */
    protected function model(): Model
    {
        if(! $this->model) {
            $this->throwError('The model class used for CRUD in the current controller is not set.');
        }
        if(! is_subclass_of($this->model, Model::class)) {
            $this->throwError('The model class used for CRUD in the current controller is incorrect.');
        }
        return new $this->model;
    }

    /**
     * 获取当前控制器的表单请求
     * Obtain the form request class used for CRUD in the current controller
     *
     * @return FormRequest
     */
    protected function formRequest(): FormRequest
    {
        if (! $this->formRequest) {
            $this->throwError('The form request class used for CRUD in the current controller is not set.');
        }
        if (! is_subclass_of($this->formRequest, FormRequest::class)) {
            $this->throwError('The form request class used for CRUD in the current controller is incorrect.');
        }
        return app($this->formRequest);
    }
}
