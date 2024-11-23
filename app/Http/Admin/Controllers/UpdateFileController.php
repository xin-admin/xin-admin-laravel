<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\PostMapping;
use App\Attribute\route\RequestMapping;
use App\Enum\FileType;
use App\Service\UpdateFileService;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/update')]
class UpdateFileController
{

    #[Autowired]
    protected UpdateFileService $updateFileService;

    /**
     * 上传图片
     */
    #[PostMapping('/image/{id}')]
    #[Authorize('admin.update.image')]
    public function image(int $id): JsonResponse
    {
        $this->updateFileService->setFileType(FileType::IMAGE);
        return $this->updateFileService->upload($id);
    }

    /**
     * 上传视频
     */
    #[PostMapping('/video/{id}')]
    #[Authorize('admin.update.video')]
    public function video(int $id): JsonResponse
    {
        $this->updateFileService->setFileType(FileType::VIDEO);
        return $this->updateFileService->upload($id);
    }

    /**
     * 上传压缩文件
     */
    #[PostMapping('/zip/{id}')]
    #[Authorize('admin.update.zip')]
    public function zip(int $id): JsonResponse
    {
        $this->updateFileService->setFileType(FileType::ZIP);
        return $this->updateFileService->upload($id);
    }

    /**
     * 上传音频文件
     */
    #[PostMapping('/mp3/{id}')]
    #[Authorize('admin.update.mp3')]
    public function mp3(int $id): JsonResponse
    {
        $this->updateFileService->setFileType(FileType::MP3);
        return $this->updateFileService->upload($id);
    }

    /**
     * 上传其他类型文件
     */
    #[PostMapping('/annex/{id}')]
    #[Authorize('admin.update.annex')]
    public function annex(int $id): JsonResponse
    {
        $this->updateFileService->setFileType(FileType::ANNEX);
        return $this->updateFileService->upload($id);
    }



}