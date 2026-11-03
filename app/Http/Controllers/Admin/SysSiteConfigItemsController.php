<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysSiteConfigItemsFormRequest;
use App\Models\SysSiteConfigItemsModel;
use App\Services\SysSiteConfigService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AttrRoute\Attribute\DeleteRoute;
use Xin\AttrRoute\Attribute\GetRoute;
use Xin\AttrRoute\Attribute\PostRoute;
use Xin\AttrRoute\Attribute\PutRoute;
use Xin\AttrRoute\Attribute\RequestAttribute;

/**
 * 系统设置
 */
#[RequestAttribute('/system/config/items', 'system.config.items')]
class SysSiteConfigItemsController extends BaseController
{
    protected array $searchField = [
        'group_id' => '=',
    ];

    /** 查询设置项列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        if (empty($params['group_id'])) {
            return $this->error('请选择设置分组');
        }
        $query = SysSiteConfigItemsModel::query();
        $data = $this->buildSearch($params, $query)
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        return $this->success($data);
    }

    /** 创建设置项 */
    #[PostRoute(authorize: 'create')]
    public function create(SysSiteConfigItemsFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysSiteConfigItemsModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑设置项 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysSiteConfigItemsFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysSiteConfigItemsModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除设置项 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        $model = SysSiteConfigItemsModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->delete();
        return $this->success();
    }

    /** 批量保存设置 */
    #[PutRoute('/save', 'save')]
    public function save(): JsonResponse
    {
        $configs = request()->input('configs');
        if (empty($configs) || !is_array($configs)) {
            return $this->error('请提供设置数据');
        }

        $result = SysSiteConfigService::batchSaveSiteConfig($configs);

        if ($result['success']) {
            SysSiteConfigService::refreshSiteConfig();
            return $this->success();
        }
        $message = '设置保存失败：' . implode('；', $result['errors']);
        return $this->error($message);
    }

    /** 刷新设置 */
    #[PostRoute('/refreshCache', 'refresh')]
    public function refreshCache(): JsonResponse
    {
        SysSiteConfigService::refreshSiteConfig();
        return $this->success();
    }
}
