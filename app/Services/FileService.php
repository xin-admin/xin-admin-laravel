<?php

namespace App\Services;

use App\Exceptions\HttpResponseException;
use App\Models\FileModel;
use App\Support\Enum\FileType;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService
{
    /**
     * 上传文件
     * @param \App\Support\Enum\FileType $fileType
     * @param int $group_id
     * @param string $disk
     * @param string $type
     * @return array
     */
    public function upload(FileType $fileType, int $group_id, string $disk, string $type = 'admin'): array
    {
        $file = request()->file('file');
        // 验证文件大小
        $file_size = $file->getSize();
        if ($file_size > $fileType->maxSize()) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.size_limit')]);
        }
        // 验证扩展名
        $file_ext = $file->extension();
        $vel_ext = $fileType->fileExt();
        if (is_array($vel_ext)) {
            if (! in_array($file_ext, $vel_ext)) {
                throw new HttpResponseException(['success' => false, 'msg' => __('system.file.ext_limit', ['ext' => $fileType->name()])]);
            }
        }else {
            if ($vel_ext !== '*' && $vel_ext !== $file_ext) {
                throw new HttpResponseException(['success' => false, 'msg' => __('system.file.ext_limit', ['ext' => $fileType->name()])]);
            }
        }
        $path = $file->store('file', $disk);
        if (! $path) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.upload_failed')]);
        }
        if ($type === 'admin') {
            $user_id = auth()->id();
            $channel = 10;
        } else {
            $user_id = auth('user')->id();
            $channel = 20;
        }
        $data = [
            'disk' => $disk, // 磁盘
            'group_id' => $group_id, // 文件分组
            'channel' => $channel,  // 来源
            'file_name' => $file->getClientOriginalName(), // 文件名
            'file_type' => $fileType->value, // 类型
            'file_path' => $path, // 地址
            'file_size' => $file->getSize(), // 大小
            'file_ext' => $file_ext, // 扩展名
            'uploader_id' => $user_id, // 上传用户ID
        ];
        $fileData = FileModel::create($data);
        return $fileData->toArray();
    }

    /**
     * 删除文件
     * @param int $fileId
     * @param bool $recycle
     * @return void
     */
    public function delete(int $fileId, bool $recycle = true): void
    {
        if ($recycle) {
            FileModel::where('file_id', $fileId)->delete();
            return;
        }
        $file = FileModel::withTrashed()->find($fileId);
        FileModel::withTrashed()->where('file_id', $fileId)->forceDelete();
        Storage::disk($file->value('disk'))->delete($file->value('file_path'));
    }

    /**
     * 获取文件 Url
     * @param int $fileId
     * @return string
     */
    public function url(int $fileId): string
    {
        $file = FileModel::find($fileId);

        return Storage::disk($file->value('disk'))->url($file->value('file_path'));
    }

    /**
     * @param int $fileId
     * @param int $expire
     * @param array $options
     * @return string
     */
    public function temporaryUrl(int $fileId, int $expire = 5, array $options = []): string
    {
        $file = FileModel::find($fileId);
        return Storage::disk($file->value('disk'))->temporaryUrl(
            $file->value('file_path'),
            now()->addMinutes($expire),
            $options
        );
    }

    /**
     * 下载文件
     * @param int $fileId
     * @return StreamedResponse
     */
    public function download(int $fileId): StreamedResponse
    {
        $file = FileModel::where('file_id', $fileId)->first();
        return Storage::disk($file->value('disk'))->download($file->value('file_path'), $file->value('file_name'));
    }
}
