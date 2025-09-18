<?php

namespace App\Http\Controllers;

use App\Repositories\Repository;
use App\Services\Service;
use App\Support\Trait\RequestJson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use RequestJson;

    /**
     * 控制器模型
     */
    protected Model $model;

    /**
     * 控制器仓储类
     */
    protected Repository $repository;

    /**
     * 控制器服务类
     */
    protected Service $service;

    /**
     * 权限验证白名单
     * Permission verification whitelist
     *
     * @var array
     */
    protected array $noPermission = [];

    /** 查询响应 */
    public function find($id): JsonResponse
    {
        $item = $this->repository->find($id);
        return $this->success($item);
    }

    /** 列表响应 */
    public function query(Request $request): JsonResponse
    {
        $list = $this->repository->list($request->query());
        return $this->success($list);
    }

    /** 更新响应 */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->repository->update($id, $request->all());
        return $this->success('ok');
    }

    /** 新增响应 */
    public function create(Request $request): JsonResponse
    {
        $this->repository->create($request->all());
        return $this->success('ok');
    }

    /** 删除响应 */
    public function delete(int $id): JsonResponse
    {
        $this->repository->delete($id);
        return $this->success('ok');
    }
}
