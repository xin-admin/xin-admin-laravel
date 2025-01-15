<?php

namespace App\Service;

use App\Enum\FileType;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface IFileService
{
    /**
     * 上传文件
     *
     * @param  FileType  $fileType  文件类型
     * @param  string  $disk  磁盘
     * @param  int  $group_id  文件分组
     * @param  string  $type  上传客户端类型
     */
    public function upload(FileType $fileType, int $group_id, string $disk, string $type): array;

    /**
     * 删除文件
     *
     * @param  int  $fileId  文件ID
     * @param  bool  $recycle  是否回收站
     */
    public function delete(int $fileId, bool $recycle): void;

    /**
     * 获取文件地址
     *
     * @param  int  $fileId  文件ID
     */
    public function url(int $fileId): string;

    /**
     * 获取文件临时地址
     * @param  int  $fileId  文件ID
     * @param  int  $expire  过期时间
     * @param  array  $options  S3 参数
     * @return mixed
     */
    public function temporaryUrl(int $fileId, int $expire, array $options): string;

    /**
     * 下载文件
     * @param  int  $fileId  文件ID
     */
    public function download(int $fileId): StreamedResponse;
}
