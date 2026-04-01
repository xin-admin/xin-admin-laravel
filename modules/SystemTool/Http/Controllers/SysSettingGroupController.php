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
use Modules\SystemTool\Http\Requests\SysSettingGroupFormRequest;
use Modules\SystemTool\Models\SysSettingGroupModel;

/**
 * 设置分组控制器
 */
#[RequestAttribute('/system/setting/group', 'system.setting.group')]
class SysSettingGroupController extends BaseController
{
    /** 查询设置分组列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $query = SysSettingGroupModel::query();

        if (!empty($params['keywordSearch'])) {
            $query->whereAny(
                ['title', 'remark', 'key'],
                'like',
                '%' . str_replace('%', '\%', $params['keywordSearch']) . '%'
            );
        }

        $data = $query->get()->toArray();
        return $this->success($data);
    }

    /** 创建设置分组 */
    #[PostRoute(authorize: 'create')]
    public function create(SysSettingGroupFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysSettingGroupModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑设置分组 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysSettingGroupFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysSettingGroupModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除设置分组 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        $model = SysSettingGroupModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $count = $model->settings()->count();
        if ($count > 0) {
            throw new RepositoryException('当前分组有未删除的设置项！');
        }
        $model->delete();
        return $this->success();
    }
}
