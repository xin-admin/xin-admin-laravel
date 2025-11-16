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
use App\Services\SysFileService;
use App\Support\Enum\FileType;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * 文件列表
 */
#[RequestMapping('/sys/file/list', 'file.list')]
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
    #[PostMapping('/upload/image')]
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
    #[PostMapping('/upload/video')]
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
    #[PostMapping('/upload/audio')]
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
    #[PostMapping('/upload/zip')]
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
    #[PostMapping('/upload/file')]
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
    #[DeleteMapping]
    public function delete(int $id): JsonResponse
    {
        $recycle = request('recycle', true);
        
        $fileService = new SysFileService;
        $fileService->delete($id, $recycle);
        
        return $this->success([], __('system.file.delete_success'));
    }

    /**
     * 永久删除文件
     */
    #[DeleteMapping('/force-delete/{id}')]
    public function forceDelete(int $id): JsonResponse
    {
        $fileService = new SysFileService;
        $fileService->delete($id, false);
        
        return $this->success([], __('system.file.force_delete_success'));
    }

    /**
     * 下载文件
     */
    #[GetMapping('/download/{id}')]
    public function download(int $id): StreamedResponse
    {
        $fileService = new SysFileService;
        return $fileService->download($id);
    }
}
