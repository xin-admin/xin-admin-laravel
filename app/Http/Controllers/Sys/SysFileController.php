<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Delete;
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
#[Query, Delete]
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
    #[PostMapping('/upload/image', 'upload')]
    public function uploadImage(): JsonResponse
    {
        $groupId = request()->filled('group_id') ? (int) request('group_id') : null;
        $result = $this->fileService->upload(FileType::IMAGE, $groupId);
        return $this->success($result);
    }

    /**
     * 上传视频
     */
    #[PostMapping('/upload/video', 'upload')]
    public function uploadVideo(): JsonResponse
    {
        $groupId = request()->filled('group_id') ? (int) request('group_id') : null;
        $result = $this->fileService->upload(FileType::VIDEO, $groupId);
        return $this->success($result);
    }

    /**
     * 上传音频
     */
    #[PostMapping('/upload/audio', 'upload')]
    public function uploadAudio(): JsonResponse
    {
        $groupId = request()->filled('group_id') ? (int) request('group_id') : null;
        $result = $this->fileService->upload(FileType::AUDIO, $groupId);
        return $this->success($result);
    }

    /**
     * 上传压缩包
     */
    #[PostMapping('/upload/zip', 'upload')]
    public function uploadZip(): JsonResponse
    {
        $groupId = request()->filled('group_id') ? (int) request('group_id') : null;
        $result = $this->fileService->upload(FileType::ZIP, $groupId);
        return $this->success($result);
    }

    /**
     * 上传其他文件
     */
    #[PostMapping('/upload/file', 'upload')]
    public function uploadFile(): JsonResponse
    {
        $groupId = request()->filled('group_id') ? (int) request('group_id') : null;
        $result = $this->fileService->upload(FileType::ANNEX, $groupId);
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
    #[DeleteMapping(authorize: 'delete')]
    public function delete(int $id): JsonResponse
    {
        $this->fileService->delete($id);
        return $this->success();
    }

    /**
     * 永久删除文件
     */
    #[DeleteMapping('/force-delete/{id}', 'force-delete')]
    public function forceDelete(int $id): JsonResponse
    {
        $this->fileService->forceDelete($id);
        return $this->success();
    }

    /**
     * 批量删除文件
     */
    #[DeleteMapping('/batch-delete', 'delete')]
    public function batchDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->fileService->batchDelete($ids);
        return $this->success(['count' => $count]);
    }

    /**
     * 批量永久删除文件
     */
    #[DeleteMapping('/batch-force-delete', 'force-delete')]
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
    #[PostMapping('/batch-restore', 'restore')]
    public function batchRestore(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->fileService->batchRestore($ids);
        return $this->success(['count' => $count]);
    }

    /**
     * 复制文件
     */
    #[PostMapping('/copy/{id}', 'copy')]
    public function copy(int $id, Request $request): JsonResponse
    {
        $targetGroupId = (int) $request->input('group_id', 0);
        $targetDisk = $request->input('disk');
        $result = $this->fileService->copy($id, $targetGroupId, $targetDisk);
        return $this->success($result);
    }

    /**
     * 移动文件
     */
    #[PostMapping('/move/{id}', 'move')]
    public function move(int $id, Request $request): JsonResponse
    {
        $targetGroupId = (int) $request->input('group_id', 0);
        $targetDisk = $request->input('disk');
        $result = $this->fileService->move($id, $targetGroupId, $targetDisk);
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
     * 更新文件分组
     */
    #[PutMapping('/group/{id}', 'group')]
    public function updateGroup(int $id, Request $request): JsonResponse
    {
        $groupId = (int) $request->input('group_id', 0);
        $this->fileService->updateGroup($id, $groupId);
        return $this->success();
    }

    /**
     * 批量更新文件分组
     */
    #[PutMapping('/batch-group', 'group')]
    public function batchUpdateGroup(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $groupId = (int) $request->input('group_id', 0);
        $count = $this->fileService->batchUpdateGroup($ids, $groupId);
        return $this->success(['count' => $count]);
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
     * 获取磁盘使用统计
     */
    #[GetMapping('/disk-usage', 'disk-usage')]
    public function getDiskUsage(Request $request): JsonResponse
    {
        $disk = $request->input('disk');
        $usage = $this->fileService->getDiskUsage($disk);
        return $this->success($usage);
    }

    /**
     * 清空回收站文件
     */
    #[DeleteMapping('/clean-trashed', 'clean-trashed')]
    public function cleanTrashed(Request $request): JsonResponse
    {
        $count = $this->fileService->cleanTrashed();
        return $this->success(['count' => $count]);
    }
}
