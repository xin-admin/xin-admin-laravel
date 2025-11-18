<?php

namespace App\Services\Sys;

use App\Exceptions\HttpResponseException;
use App\Models\Sys\SysFileModel;
use App\Support\Enum\FileType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SysFileService
{
    /**
     * 上传文件
     * @param FileType $fileType
     * @param int $group_id
     * @param string $disk
     * @param int $channel
     * @return array
     */
    public function upload(FileType $fileType, int $group_id, string $disk, int $channel = 10): array
    {
        try {
            $file = request()->file('file');
            if (!$file) {
                throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
            }
            
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
            } else {
                if ($vel_ext !== '*' && $vel_ext !== $file_ext) {
                    throw new HttpResponseException(['success' => false, 'msg' => __('system.file.ext_limit', ['ext' => $fileType->name()])]);
                }
            }
            
            $path = $file->store('file', $disk);
            if (! $path) {
                throw new HttpResponseException(['success' => false, 'msg' => __('system.file.upload_failed')]);
            }
            if (Auth::check()) {
                $user_id = Auth::id();
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
                'uploader_id' => $user_id ?? null, // 上传用户ID
            ];
            
            $fileData = SysFileModel::create($data);
            return $fileData->toArray();
            
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage(), [
                'file_type' => $fileType->name,
                'group_id' => $group_id,
                'disk' => $disk,
                'trace' => $e->getTraceAsString()
            ]);
            
            // 如果是HttpResponseException，直接抛出
            if ($e instanceof HttpResponseException) {
                throw $e;
            }
            
            // 其他异常转换为HttpResponseException
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.upload_failed')]);
        }
    }

    /**
     * 删除文件
     * @param int $fileId
     * @param bool $recycle
     * @return void
     */
    public function delete(int $fileId, bool $recycle = true): void
    {
        try {
            if ($recycle) {
                SysFileModel::where('id', $fileId)->delete();
                return;
            }
            
            $file = SysFileModel::withTrashed()->find($fileId);
            if (!$file) {
                throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
            }
            
            SysFileModel::withTrashed()->where('id', $fileId)->forceDelete();
            Storage::disk($file->disk)->delete($file->file_path);
            
        } catch (\Exception $e) {
            Log::error('File deletion failed: ' . $e->getMessage(), [
                'file_id' => $fileId,
                'recycle' => $recycle,
                'trace' => $e->getTraceAsString()
            ]);
            
            // 如果是HttpResponseException，直接抛出
            if ($e instanceof HttpResponseException) {
                throw $e;
            }
            
            // 其他异常转换为HttpResponseException
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.delete_failed')]);
        }
    }

    /**
     * 下载文件
     * @param int $fileId
     * @return StreamedResponse
     */
    public function download(int $fileId): StreamedResponse
    {
        try {
            $file = SysFileModel::where('id', $fileId)->first();
            if (!$file) {
                throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
            }
            
            return Storage::disk($file->disk)->download($file->file_path, $file->file_name);
            
        } catch (\Exception $e) {
            Log::error('File download failed: ' . $e->getMessage(), [
                'file_id' => $fileId,
                'trace' => $e->getTraceAsString()
            ]);
            
            // 如果是HttpResponseException，直接抛出
            if ($e instanceof HttpResponseException) {
                throw $e;
            }
            
            // 其他异常转换为HttpResponseException
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.download_failed')]);
        }
    }
}
