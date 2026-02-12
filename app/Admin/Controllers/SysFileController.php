<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\SysFileMoveOrCopyRequest;
use App\Admin\Services\SysFileService;
use App\Common\Controllers\BaseController;
use App\Common\Enum\FileType;
use App\Common\Services\AnnoRoute\Crud\Query;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\DeleteRoute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use App\Common\Services\AnnoRoute\Route\PostRoute;
use App\Common\Services\AnnoRoute\Route\PutRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * 文件列表
 */
#[RequestAttribute('/system/file/list', 'system.file.list')]
#[Query]
class SysFileController extends BaseController
{

    public function __construct(
        protected SysFileService $service
    ) {}

    /**
     * 上传图片
     */
    #[PostRoute('/upload', 'upload')]
    public function uploadImage(): JsonResponse
    {
        $fileType = request()->filled('file_type') ? (int) request('file_type') : 10;
        $groupId = request()->filled('group_id') ? (int) request('group_id') : null;
        $result = $this->service->upload(FileType::from($fileType), $groupId);
        return $this->success($result);
    }

    /**
     * 获取回收站文件列表
     */
    #[GetRoute('/trashed', 'system.file.list.trashed')]
    public function trashed(): JsonResponse
    {
        $list = $this->service->getTrashedList(request()->all());
        return $this->success($list);
    }

    /**
     * 删除文件（软删除）
     */
    #[DeleteRoute('/{id}', authorize: 'delete', where: ['id' => '[0-9]+'])]
    public function delete(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->success();
    }

    /**
     * 批量删除文件
     */
    #[DeleteRoute('/batch/delete', 'delete')]
    public function batchDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->service->batchDelete($ids);
        return $this->success(['count' => $count]);
    }

    /**
     * 彻底删除文件
     */
    #[DeleteRoute('/force-delete/{id}', 'force-delete', where: ['id' => '[0-9]+'])]
    public function forceDelete(int $id): JsonResponse
    {
        $this->service->forceDelete($id);
        return $this->success();
    }

    /**
     * 批量彻底删除文件
     */
    #[DeleteRoute('/batch/force-delete', 'force-delete')]
    public function batchForceDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->service->batchForceDelete($ids);
        return $this->success(['count' => $count]);
    }

    /**
     * 恢复文件
     */
    #[PostRoute('/restore/{id}', 'restore', where: ['id' => '[0-9]+'])]
    public function restore(int $id): JsonResponse
    {
        $this->service->restore($id);
        return $this->success();
    }

    /**
     * 批量恢复文件
     */
    #[PostRoute('/batch/restore', 'restore')]
    public function batchRestore(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->service->batchRestore($ids);
        return $this->success(['count' => $count]);
    }

    /**
     * 复制文件
     */
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

    /**
     * 移动文件
     */
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

    /**
     * 重命名文件
     */
    #[PutRoute('/rename/{id}', 'rename', where: ['id' => '[0-9]+'])]
    public function rename(int $id, Request $request): JsonResponse
    {
        $newName = $request->input('name');
        $this->service->rename($id, $newName);
        return $this->success();
    }

    /**
     * 下载文件
     */
    #[GetRoute('/download/{id}', 'download', where: ['id' => '[0-9]+'])]
    public function download(int $id): StreamedResponse
    {
        return $this->service->download($id);
    }

    /**
     * 清空回收站文件
     */
    #[DeleteRoute('/clean/trashed', 'clean-trashed')]
    public function cleanTrashed(Request $request): JsonResponse
    {
        $count = $this->service->cleanTrashed();
        return $this->success(['count' => $count]);
    }
}
