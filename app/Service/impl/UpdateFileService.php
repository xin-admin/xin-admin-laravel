<?php

namespace App\Service\impl;

use App\Enum\FileType;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Xin\File;

class UpdateFileService
{
    use RequestJson;

    /**
     * 文件类型
     */
    private FileType $fileType;

    /**
     * 储存磁盘
     */
    private string $disk = 'public';

    /**
     * 分组ID
     */
    private int $groupId = 0;

    /**
     * 上传用户ID
     */
    private int $userId;

    /**
     * 上传来源 10：app； 20：admin；
     */
    private int $channel;

    public function upload($group_id): JsonResponse
    {
        $storage = new File;
        $user_id = AdminUserService::getAdminUserId();
        $fileInfo = $storage->upload(
            $this->fileType->value,
            $this->disk,
            $group_id,
            $user_id,
            20
        );

        return $this->success(['fileInfo' => $fileInfo], '图片上传成功');
    }

    public function setFileType(FileType $type): void
    {
        $this->fileType = $type;
    }

    public function setDisk(string $disk): void
    {
        $this->disk = $disk;
    }
}
