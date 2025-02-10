<?php

namespace App\Service\impl;

use App\Enum\FileType;
use App\Exceptions\HttpResponseException;
use App\Models\FileModel;
use App\Service\IFileService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService implements IFileService
{
    public function upload(FileType $fileType, int $group_id, string $disk, string $type = 'admin'): array
    {
        $file = request()->file('file');
        $file_ext = $file->extension();
        $file_size = $file->getSize();
        // 验证文件大小
        if ($file_size > $fileType->maxSize()) {
            throw new HttpResponseException(['success' => false, 'msg' => '上传文件大小超限制！']);
        }
        $vel_ext = $fileType->fileExt();
        // 验证扩展名
        if ($vel_ext != '*') {
            if (is_array($vel_ext)) {
                if (! in_array($file_ext, $vel_ext)) {
                    throw new HttpResponseException(['success' => false, 'msg' => "不支持的{$fileType->name()}类型"]);
                }
            }
            if ($vel_ext !== $file_ext) {
                throw new HttpResponseException(['success' => false, 'msg' => "不支持的{$fileType->name()}类型"]);
            }
        }
        $path = $file->store('file', $disk);
        if (! $path) {
            throw new HttpResponseException(['success' => false, 'msg' => '上传失败！']);
        }
        if ($type === 'admin') {
            $user_id = customAuth('admin')->id();
            $channel = 10;
        } else {
            $user_id = customAuth('user')->id();
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

    public function url(int $fileId): string
    {
        $file = FileModel::find($fileId);

        return Storage::disk($file->value('disk'))->url($file->value('file_path'));
    }

    public function temporaryUrl(int $fileId, int $expire = 5, array $options = []): string
    {
        $file = FileModel::find($fileId);
        return Storage::disk($file->value('disk'))->temporaryUrl(
            $file->value('file_path'),
            now()->addMinutes($expire),
            $options
        );
    }

    public function download(int $fileId): StreamedResponse
    {
        $file = FileModel::where('file_id', $fileId)->first();
        return Storage::disk($file->value('disk'))->download($file->value('file_path'), $file->value('file_name'));
    }
}
