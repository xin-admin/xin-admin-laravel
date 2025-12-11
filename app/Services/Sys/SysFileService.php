<?php

namespace App\Services\Sys;

use App\Exceptions\HttpResponseException;
use App\Models\Sys\SysFileModel;
use App\Services\StorageConfigService;
use App\Support\Enum\FileType;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * 文件服务类
 * 提供完整的文件管理功能，包括上传、下载、复制、移动、删除等操作
 */
class SysFileService
{
    /**
     * 获取存储磁盘实例
     */
    protected function disk(?string $disk = null): FilesystemAdapter
    {
        $diskName = $disk ?? StorageConfigService::getDefaultDisk();
        if ($diskName === 's3' && !StorageConfigService::isS3Configured()) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.storage.s3_not_configured')]);
        }
        /** @var FilesystemAdapter */
        return Storage::disk($diskName);
    }

    /**
     * 生成存储路径（结合分组ID）
     */
    protected function generateStoragePath(int $groupId, string $extension): string
    {
        $directory = $groupId > 0 ? "file/{$groupId}" : 'file';
        $filename = date('Ymd') . '/' . uniqid() . '.' . $extension;
        return $directory . '/' . $filename;
    }

    /**
     * 上传文件
     */
    public function upload(
        FileType $fileType,
        ?int $groupId = null,
        int $channel = 10,
    ): array {
        $disk = StorageConfigService::getDefaultDisk();
        $groupId = $groupId ?? 0;

        $file = request()->file('file');
        if (!$file instanceof UploadedFile) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }

        $this->validateFile($file, $fileType);

        $fileExt = strtolower($file->getClientOriginalExtension()) ?: $file->extension();
        $storagePath = $this->generateStoragePath($groupId, $fileExt);
        
        // 存储文件并设置可见性
        $stored = $this->disk($disk)->put($storagePath, $file->getContent(), 'public');
        if (!$stored) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.upload_failed')]);
        }
        
        $fileData = SysFileModel::create([
            'disk' => $disk,
            'group_id' => $groupId,
            'channel' => $channel,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $fileType->value,
            'file_path' => $storagePath,
            'file_size' => $file->getSize(),
            'file_ext' => $fileExt,
            'uploader_id' => Auth::id(),
        ]);
        
        return $fileData->toArray();
    }

    /**
     * 通过内容直接存储文件
     */
    public function store(
        string $content,
        string $filename,
        FileType $fileType,
        ?int $groupId = null,
    ): array {
        $disk = StorageConfigService::getDefaultDisk();
        $groupId = $groupId ?? 0;

        $fileExt = pathinfo($filename, PATHINFO_EXTENSION) ?: 'bin';
        $storagePath = $this->generateStoragePath($groupId, $fileExt);
        
        $stored = $this->disk($disk)->put($storagePath, $content, 'public');
        if (!$stored) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.upload_failed')]);
        }
        
        $fileData = SysFileModel::create([
            'disk' => $disk,
            'group_id' => $groupId,
            'channel' => 10,
            'file_name' => $filename,
            'file_type' => $fileType->value,
            'file_path' => $storagePath,
            'file_size' => strlen($content),
            'file_ext' => $fileExt,
            'uploader_id' => Auth::id(),
        ]);
        
        return $fileData->toArray();
    }

    /**
     * 验证上传文件
     */
    protected function validateFile(UploadedFile $file, FileType $fileType): void
    {
        // 验证文件类型的大小限制
        if ($file->getSize() > $fileType->maxSize()) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.size_limit')]);
        }
        
        $fileExt = strtolower($file->getClientOriginalExtension()) ?: $file->extension();
        
        // 验证文件类型的扩展名限制
        $allowedExt = $fileType->fileExt();
        if (is_array($allowedExt)) {
            if (!in_array($fileExt, $allowedExt)) {
                throw new HttpResponseException(['success' => false, 'msg' => __('system.file.ext_limit', ['ext' => $fileType->name()])]);
            }
        } elseif ($allowedExt !== '*' && $allowedExt !== $fileExt) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.ext_limit', ['ext' => $fileType->name()])]);
        }
    }

    /**
     * 软删除文件（移入回收站）
     */
    public function delete(int $fileId): bool
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        return (bool) $file->delete();
    }

    /**
     * 批量软删除文件
     */
    public function batchDelete(array $fileIds): int
    {
        return SysFileModel::whereIn('id', $fileIds)->delete();
    }

    /**
     * 恢复已删除的文件
     */
    public function restore(int $fileId): bool
    {
        $file = SysFileModel::withTrashed()->find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        return $file->restore();
    }

    /**
     * 批量恢复文件
     */
    public function batchRestore(array $fileIds): int
    {
        return SysFileModel::withTrashed()->whereIn('id', $fileIds)->restore();
    }

    /**
     * 彻底删除文件（含物理删除）
     */
    public function forceDelete(int $fileId): bool
    {
        $file = SysFileModel::withTrashed()->find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        // 删除物理文件
        $this->disk($file->disk)->delete($file->file_path);
        
        return (bool) $file->forceDelete();
    }

    /**
     * 批量彻底删除文件
     */
    public function batchForceDelete(array $fileIds): int
    {
        $files = SysFileModel::withTrashed()->whereIn('id', $fileIds)->get();
        $count = 0;
        
        foreach ($files as $file) {
            $this->disk($file->disk)->delete($file->file_path);
            $file->forceDelete();
            $count++;
        }
        
        return $count;
    }

    /**
     * 下载文件
     */
    public function download(int $fileId, ?string $filename = null): StreamedResponse
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        $downloadName = $filename ?? $file->file_name;
        return $this->disk($file->disk)->download($file->file_path, $downloadName);
    }

    /**
     * 获取文件流式响应（用于在线预览等场景）
     */
    public function stream(int $fileId): StreamedResponse
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        $mimeType = $this->getMimeType($file->file_ext);
        
        return response()->stream(
            function () use ($file) {
                $stream = $this->disk($file->disk)->readStream($file->file_path);
                fpassthru($stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            },
            200,
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $file->file_name . '"',
            ]
        );
    }

    /**
     * 获取文件内容
     */
    public function getContent(int $fileId): string
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        return $this->disk($file->disk)->get($file->file_path);
    }

    /**
     * 获取文件访问URL
     */
    public function getUrl(int $fileId): ?string
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            return null;
        }
        
        return $this->disk($file->disk)->url($file->file_path);
    }

    /**
     * 根据路径获取访问URL
     */
    public function getUrlByPath(string $path, ?string $disk = null): string
    {
        return $this->disk($disk)->url($path);
    }

    /**
     * 获取文件元数据信息
     */
    public function getMetadata(int $fileId): ?array
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            return null;
        }
        
        $disk = $this->disk($file->disk);
        
        return [
            'id' => $file->id,
            'name' => $file->file_name,
            'path' => $file->file_path,
            'disk' => $file->disk,
            'size' => $file->file_size,
            'extension' => $file->file_ext,
            'mime_type' => $this->getMimeType($file->file_ext),
            'last_modified' => $disk->exists($file->file_path) 
                ? date('Y-m-d H:i:s', $disk->lastModified($file->file_path)) 
                : null,
            'url' => $this->getUrl($file->id),
            'group_id' => $file->group_id,
            'uploader_id' => $file->uploader_id,
            'created_at' => $file->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $file->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * 复制文件
     */
    public function copy(int $fileId, int $targetGroupId = 0, ?string $targetDisk = null): array
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        $targetDisk = $targetDisk ?? $file->disk;
        $newPath = $this->generateStoragePath($targetGroupId, $file->file_ext);
        
        // 同磁盘复制
        if ($targetDisk === $file->disk) {
            $this->disk($file->disk)->copy($file->file_path, $newPath);
        } else {
            // 跨磁盘复制
            $content = $this->disk($file->disk)->get($file->file_path);
            $this->disk($targetDisk)->put($newPath, $content, 'public');
        }
        
        // 创建新的文件记录
        $newFile = SysFileModel::create([
            'disk' => $targetDisk,
            'group_id' => $targetGroupId,
            'channel' => $file->channel,
            'file_name' => $file->file_name,
            'file_type' => $file->file_type,
            'file_path' => $newPath,
            'file_size' => $file->file_size,
            'file_ext' => $file->file_ext,
            'uploader_id' => Auth::id(),
        ]);
        
        return $newFile->toArray();
    }

    /**
     * 移动文件
     */
    public function move(int $fileId, int $targetGroupId = 0, ?string $targetDisk = null): array
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        $targetDisk = $targetDisk ?? $file->disk;
        $oldPath = $file->file_path;
        $oldDisk = $file->disk;
        
        // 同磁盘同分组，无需移动
        if ($targetDisk === $file->disk && $targetGroupId === $file->group_id) {
            return $file->toArray();
        }
        
        $newPath = $this->generateStoragePath($targetGroupId, $file->file_ext);
        
        // 同磁盘移动
        if ($targetDisk === $file->disk) {
            $this->disk($file->disk)->move($oldPath, $newPath);
        } else {
            // 跨磁盘移动
            $content = $this->disk($oldDisk)->get($oldPath);
            $this->disk($targetDisk)->put($newPath, $content, 'public');
            $this->disk($oldDisk)->delete($oldPath);
        }
        
        // 更新文件记录
        $file->update([
            'disk' => $targetDisk,
            'group_id' => $targetGroupId,
            'file_path' => $newPath,
        ]);
        
        return $file->fresh()->toArray();
    }

    /**
     * 重命名文件
     */
    public function rename(int $fileId, string $newName): bool
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        return $file->update(['file_name' => $newName]);
    }

    /**
     * 更新文件分组
     */
    public function updateGroup(int $fileId, int $groupId): bool
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            throw new HttpResponseException(['success' => false, 'msg' => __('system.file.not_found')]);
        }
        
        return $file->update(['group_id' => $groupId]);
    }

    /**
     * 批量更新文件分组
     */
    public function batchUpdateGroup(array $fileIds, int $groupId): int
    {
        return SysFileModel::whereIn('id', $fileIds)->update(['group_id' => $groupId]);
    }

    /**
     * 检查文件是否存在
     */
    public function exists(int $fileId): bool
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            return false;
        }
        
        return $this->disk($file->disk)->exists($file->file_path);
    }

    /**
     * 获取文件大小（字节）
     */
    public function getSize(int $fileId): ?int
    {
        $file = SysFileModel::find($fileId);
        if (!$file) {
            return null;
        }
        
        return $this->disk($file->disk)->size($file->file_path);
    }

    /**
     * 获取MIME类型
     */
    protected function getMimeType(string $extension): string
    {
        $mimeTypes = [
            // 图片
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            // 音频
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'flac' => 'audio/flac',
            'aac' => 'audio/aac',
            // 视频
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'mkv' => 'video/x-matroska',
            'mov' => 'video/quicktime',
            'avi' => 'video/x-msvideo',
            // 文档
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            // 压缩包
            'zip' => 'application/zip',
            'rar' => 'application/vnd.rar',
            '7z' => 'application/x-7z-compressed',
            // 其他
            'json' => 'application/json',
            'xml' => 'application/xml',
            'txt' => 'text/plain',
        ];
        
        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }

    /**
     * 清空回收站文件
     */
    public function cleanTrashed(): int
    {
        $expiredFiles = SysFileModel::onlyTrashed()->get();
        $count = 0;
        foreach ($expiredFiles as $file) {
            $this->disk($file->disk)->delete($file->file_path);
            $file->forceDelete();
            $count++;
        }
        return $count;
    }

    /**
     * 获取磁盘使用统计
     */
    public function getDiskUsage(?string $disk = null): array
    {
        $query = SysFileModel::query();
        
        if ($disk) {
            $query->where('disk', $disk);
        }
        
        return [
            'total_files' => $query->count(),
            'total_size' => $query->sum('file_size'),
            'by_type' => $query->selectRaw('file_type, COUNT(*) as count, SUM(file_size) as size')
                ->groupBy('file_type')
                ->get()
                ->keyBy('file_type')
                ->toArray(),
        ];
    }
}
