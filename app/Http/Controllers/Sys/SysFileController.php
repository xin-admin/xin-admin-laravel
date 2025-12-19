<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Sys\SysFileMoveOrCopyRequest;
use App\Providers\AnnoRoute\Attribute\DeleteMapping;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysFileRepository;
use App\Services\Sys\SysFileService;
use App\Support\Enum\FileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * 文件列表
 */
#[RequestMapping('/sys/file/list', 'system.file.list')]
#[Query]
class SysFileController extends BaseController
{
    public function __construct(protected SysFileService $fileService) {}

    protected function repository(): RepositoryInterface
    {
        return app(SysFileRepository::class);
    }

    /**
     * 上传图片
     */
    #[PostMapping('/upload', 'upload')]
    public function uploadImage(): JsonResponse
    {
        $fileType = request()->filled('file_type') ? (int) request('file_type') : 10;
        $groupId = request()->filled('group_id') ? (int) request('group_id') : null;
        $result = $this->fileService->upload(FileType::from($fileType), $groupId);
        return $this->success($result);
    }

    /**
     * 获取回收站文件列表
     */
    #[GetMapping('/trashed', 'system.file.list.trashed')]
    public function trashed(SysFileRepository $repository): JsonResponse
    {
        $list = $repository->getTrashedList(request()->all());
        return $this->success($list);
    }

    /**
     * 删除文件（软删除）
     */
    #[DeleteMapping('/{id}', authorize: 'delete')]
    public function delete(int $id): JsonResponse
    {
        $this->fileService->delete($id);
        return $this->success();
    }

    /**
     * 批量删除文件
     */
    #[DeleteMapping('/batch/delete', 'delete')]
    public function batchDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->fileService->batchDelete($ids);
        return $this->success(['count' => $count]);
    }

    /**
     * 彻底删除文件
     */
    #[DeleteMapping('/force-delete/{id}', 'force-delete')]
    public function forceDelete(int $id): JsonResponse
    {
        $this->fileService->forceDelete($id);
        return $this->success();
    }

    /**
     * 批量彻底删除文件
     */
    #[DeleteMapping('/batch/force-delete', 'force-delete')]
    public function batchForceDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->fileService->batchForceDelete($ids);
        return $this->success(['count' => $count]);
    }

    /**
     * 恢复文件
     */
    #[PostMapping('/restore/{id}', 'restore')]
    public function restore(int $id): JsonResponse
    {
        $this->fileService->restore($id);
        return $this->success();
    }

    /**
     * 批量恢复文件
     */
    #[PostMapping('/batch/restore', 'restore')]
    public function batchRestore(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->fileService->batchRestore($ids);
        return $this->success(['count' => $count]);
    }

    /**
     * 复制文件
     */
    #[PostMapping('/copy', 'copy')]
    public function copy(SysFileMoveOrCopyRequest $request): JsonResponse
    {
        $data = $request->validated();
        if(! is_array($data['ids'])) {
            $result = $this->fileService->copy($data['ids'], $data['group_id']);
        } else {
            $result = $this->fileService->batchCopy($data['ids'], $data['group_id']);
        }
        return $this->success($result);
    }

    /**
     * 移动文件
     */
    #[PostMapping('/move', 'move')]
    public function move(SysFileMoveOrCopyRequest $request): JsonResponse
    {
        $data = $request->validated();
        if(! is_array($data['ids'])) {
            $result = $this->fileService->move($data['ids'], $data['group_id']);
        } else {
            $result = $this->fileService->batchMove($data['ids'], $data['group_id']);
        }
        return $this->success($result);
    }

    /**
     * 重命名文件
     */
    #[PutMapping('/rename/{id}', 'rename')]
    public function rename(int $id, Request $request): JsonResponse
    {
        $newName = $request->input('name');
        $this->fileService->rename($id, $newName);
        return $this->success();
    }

    /**
     * 下载文件
     */
    #[GetMapping('/download/{id}', 'download')]
    public function download(int $id): StreamedResponse
    {
        return $this->fileService->download($id);
    }

    /**
     * 清空回收站文件
     */
    #[DeleteMapping('/clean/trashed', 'clean-trashed')]
    public function cleanTrashed(Request $request): JsonResponse
    {
        $count = $this->fileService->cleanTrashed();
        return $this->success(['count' => $count]);
    }
}
