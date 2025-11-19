<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\DeleteMapping;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysFileRepository;
use App\Services\Sys\SysFileService;
use App\Support\Enum\FileType;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * 文件列表
 */
#[RequestMapping('/sys/file/list', 'system.file.list')]
#[Query, Delete]
class SysFileController extends BaseController
{
    protected array $noPermission = ['download'];

    protected function repository(): RepositoryInterface
    {
        return app(SysFileRepository::class);
    }

    /**
     * 上传图片
     */
    #[PostMapping('/upload/image', 'upload')]
    public function uploadImage(SysFileService $fileService): JsonResponse
    {
        $group_id = request('group_id', 0);
        $disk = request('disk', 'public');
        $result = $fileService->upload(FileType::IMAGE, $group_id, $disk);
        return $this->success($result);
    }

    /**
     * 上传视频
     */
    #[PostMapping('/upload/video', 'upload')]
    public function uploadVideo(SysFileService $fileService): JsonResponse
    {
        $group_id = request('group_id', 0);
        $disk = request('disk', 'public');
        $result = $fileService->upload(FileType::VIDEO, $group_id, $disk);
        return $this->success($result);
    }

    /**
     * 上传音频
     */
    #[PostMapping('/upload/audio', 'upload')]
    public function uploadAudio(SysFileService $fileService): JsonResponse
    {
        $group_id = request('group_id', 0);
        $disk = request('disk', 'public');
        $result = $fileService->upload(FileType::AUDIO, $group_id, $disk);
        return $this->success($result);
    }

    /**
     * 上传压缩包
     */
    #[PostMapping('/upload/zip', 'upload')]
    public function uploadZip(SysFileService $fileService): JsonResponse
    {
        $group_id = request('group_id', 0);
        $disk = request('disk', 'public');
        $result = $fileService->upload(FileType::ZIP, $group_id, $disk);
        return $this->success($result);
    }

    /**
     * 上传其他文件
     */
    #[PostMapping('/upload/file', 'upload')]
    public function uploadFile(SysFileService $fileService): JsonResponse
    {
        $group_id = request('group_id', 0);
        $disk = request('disk', 'public');
        $result = $fileService->upload(FileType::ANNEX, $group_id, $disk);
        return $this->success($result);
    }

    /**
     * 删除文件
     */
    #[DeleteMapping(authorize: 'delete')]
    public function delete(int $id): JsonResponse
    {
        $fileService = new SysFileService;
        $fileService->delete($id);
        return $this->success();
    }

    /**
     * 永久删除文件
     */
    #[DeleteMapping('/force-delete/{id}', 'force-delete')]
    public function forceDelete(int $id): JsonResponse
    {
        $fileService = new SysFileService;
        $fileService->delete($id, false);
        return $this->success();
    }

    /**
     * 下载文件
     */
    #[GetMapping('/download/{id}', 'download')]
    public function download(int $id): StreamedResponse
    {
        $fileService = new SysFileService;
        return $fileService->download($id);
    }
}
