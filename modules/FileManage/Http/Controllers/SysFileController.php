<?php

namespace Modules\FileManage\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AnnoRoute\Attribute\DeleteRoute;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\PutRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\FileManage\Models\SysFileModel;
use Modules\FileManage\Services\SysFileService;
use Modules\SystemTool\Http\Requests\SysFileMoveOrCopyRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * 文件列表
 */
#[RequestAttribute('/system/file/list', 'system.file.list')]
class SysFileController extends BaseController
{
    protected array $searchField = [
        'group_id' => '=',
        'name' => 'like',
        'file_type' => '=',
    ];

    public function __construct(
        protected SysFileService $service
    ) {}

    /** 查询文件列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $pageSize = $params['pageSize'] ?? 10;
        $query = SysFileModel::query();
        $data = $this->buildSearch($params, $query)
            ->paginate($pageSize)
            ->toArray();
        return $this->success($data);
    }

    /** 上传文件 */
    #[PostRoute('/upload', 'upload')]
    public function uploadImage(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => 'required|file',
            'group_id' => [
                'required', 'integer',
                function ($attribute, $value, $fail) {
                    if ($value == 0) {
                        return;
                    }
                    if (!\DB::table('sys_file_group')->where('id', $value)->exists()) {
                        $fail('所选的分组 ID 不存在。');
                    }
                },
            ],
        ]);
        $result = $this->service->upload(
            $data['file'],
            $data['group_id'],
            10,
            Auth::id()
        );
        return $this->success($result);
    }

    /** 获取回收站文件列表 */
    #[GetRoute('/trashed', 'trashed')]
    public function trashed(): JsonResponse
    {
        $list = $this->service->getTrashedList(request()->all());
        return $this->success($list);
    }

    /** 删除文件（软删除） */
    #[DeleteRoute('/{id}', authorize: 'delete', where: ['id' => '[0-9]+'])]
    public function delete(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->success();
    }

    /** 批量删除文件 */
    #[DeleteRoute('/batch/delete', 'delete')]
    public function batchDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->service->batchDelete($ids);
        return $this->success(['count' => $count]);
    }

    /** 彻底删除文件 */
    #[DeleteRoute('/force-delete/{id}', 'force-delete', where: ['id' => '[0-9]+'])]
    public function forceDelete(int $id): JsonResponse
    {
        $this->service->forceDelete($id);
        return $this->success();
    }

    /** 批量彻底删除文件 */
    #[DeleteRoute('/batch/force-delete', 'force-delete')]
    public function batchForceDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->service->batchForceDelete($ids);
        return $this->success(['count' => $count]);
    }

    /** 恢复文件 */
    #[PostRoute('/restore/{id}', 'restore', where: ['id' => '[0-9]+'])]
    public function restore(int $id): JsonResponse
    {
        $this->service->restore($id);
        return $this->success();
    }

    /** 批量恢复文件 */
    #[PostRoute('/batch/restore', 'restore')]
    public function batchRestore(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->service->batchRestore($ids);
        return $this->success(['count' => $count]);
    }

    /** 复制文件 */
    #[PostRoute('/copy', 'copy')]
    public function copy(SysFileMoveOrCopyRequest $request): JsonResponse
    {
        $data = $request->validated();
        if(! is_array($data['ids'])) {
            $result = $this->service->copy($data['ids'], $data['group_id']);
        } else {
            $result = $this->service->batchCopy($data['ids'], $data['group_id']);
        }
        return $this->success($result);
    }

    /** 移动文件 */
    #[PostRoute('/move', 'move')]
    public function move(SysFileMoveOrCopyRequest $request): JsonResponse
    {
        $data = $request->validated();
        if(! is_array($data['ids'])) {
            $result = $this->service->move($data['ids'], $data['group_id']);
        } else {
            $result = $this->service->batchMove($data['ids'], $data['group_id']);
        }
        return $this->success($result);
    }

    /** 重命名文件 */
    #[PutRoute('/rename/{id}', 'rename', where: ['id' => '[0-9]+'])]
    public function rename(int $id, Request $request): JsonResponse
    {
        $newName = $request->input('name');
        $this->service->rename($id, $newName);
        return $this->success();
    }

    /** 下载文件 */
    #[GetRoute('/download/{id}', false, where: ['id' => '[0-9]+'])]
    public function download(int $id): StreamedResponse
    {
        return $this->service->download($id);
    }

    /** 清空回收站文件 */
    #[DeleteRoute('/clean/trashed', 'clean-trashed')]
    public function cleanTrashed(Request $request): JsonResponse
    {
        $count = $this->service->cleanTrashed();
        return $this->success(['count' => $count]);
    }
}
