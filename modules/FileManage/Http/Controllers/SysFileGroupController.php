<?php

namespace Modules\FileManage\Http\Controllers;

use App\Exceptions\RepositoryException;
use Illuminate\Http\JsonResponse;
use Modules\AnnoRoute\Attribute\DeleteRoute;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\PutRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\FileManage\Http\Requests\SysFileGroupFormRequest;
use Modules\FileManage\Models\SysFileGroupModel;

/**
 * 文件分组控制器
 */
#[RequestAttribute('/system/file/group', 'system.file.group')]
class SysFileGroupController extends BaseController
{
    public function __construct() {}

    /** 获取文件分组列表 */
    #[GetRoute(authorize: 'query')]
    public function list(): JsonResponse
    {
        $query = SysFileGroupModel::query()->orderBy('sort', 'asc');
        $keywordSearch = request()->input('keywordSearch', '');
        if (isset($keywordSearch) && $keywordSearch != '') {
            $query->whereAny(
                ['name'],
                'like',
                '%' . str_replace('%', '\%', $keywordSearch) . '%'
            );
            return $this->success($query->get()->toArray());
        }
        $group = $query->get()->toArray();
        return $this->success(getTreeData($group));
    }

    /** 创建文件分组 */
    #[PostRoute(authorize: 'create')]
    public function create(SysFileGroupFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysFileGroupModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑文件分组 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysFileGroupFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysFileGroupModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除文件分组 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        $model = SysFileGroupModel::find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        if ($model->countFiles > 0) {
            throw new RepositoryException('该文件夹下存在文件，无法删除');
        }
        $model->delete();
        return $this->success();
    }
}
