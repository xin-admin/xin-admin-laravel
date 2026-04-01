<?php

namespace Modules\SystemTool\Http\Controllers;

use App\Exceptions\RepositoryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\AnnoRoute\Attribute\DeleteRoute;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\PutRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\SystemTool\Http\Requests\SysSettingItemsFormRequest;
use Modules\SystemTool\Models\SysSettingItemsModel;
use Modules\SystemTool\Services\SysSettingService;

/**
 * 系统设置
 */
#[RequestAttribute('/system/setting/items', 'system.setting.items')]
class SysSettingItemsController extends BaseController
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
            throw new RepositoryException('请选择设置分组');
        }
        $query = SysSettingItemsModel::query();
        $data = $this->buildSearch($params, $query)
            ->get()
            ->toArray();
        return $this->success($data);
    }

    /** 创建设置项 */
    #[PostRoute(authorize: 'create')]
    public function create(SysSettingItemsFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysSettingItemsModel::create($validated);
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
    public function update(int $id, SysSettingItemsFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysSettingItemsModel::find($id);
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
        $model = SysSettingItemsModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->delete();
        return $this->success();
    }

    /** 保存设置 */
    #[PutRoute('/save/{id}', 'save')]
    public function save(int $id, SysSettingService $service): JsonResponse
    {
        $values = request()->input('values');
        if (is_null($values)) {
            return $this->error('请提供设置值');
        }
        $service->setSetting($id, $values);
        return $this->success('保存成功');
    }

    /** 刷新设置 */
    #[PostRoute('/refreshCache', 'refresh')]
    public function refreshCache(): JsonResponse
    {
        SysSettingService::refreshSettings();
        return $this->success('重载成功');
    }
}
