<?php

namespace App\Common\Enum;

/**
 * 枚举类：文件类型
 * Class FileType
 */
enum FileType: int
{
    // 图片
    case IMAGE = 10;

    // 音频
    case AUDIO = 20;

    // 视频
    case VIDEO = 30;

    // 压缩包
    case ZIP = 40;

    // 文档
    case DOCUMENT = 50;

    // 未知文件
    case ANNEX = 99;

    /**
     * 获取类型名称
     */
    public function name(): string
    {
        return match ($this) {
            self::IMAGE => __('system.file.image'),
            self::AUDIO => __('system.file.audio'),
            self::VIDEO => __('system.file.video'),
            self::ZIP => __('system.file.zip'),
            self::DOCUMENT => __('system.file.document'),
            self::ANNEX => __('system.file.annex'),
        };
    }

    /**
     * 获取预览地址
     */
    public function previewPath(): string
    {
        return match ($this) {
            self::IMAGE => 'static/image.svg',
            self::AUDIO => 'static/audio.svg',
            self::VIDEO => 'static/video.svg',
            self::ZIP => 'static/zip.svg',
            self::DOCUMENT => 'static/document.svg',
            self::ANNEX => 'static/annex.svg',
        };
    }

    /**
     * 根据扩展名推断文件类型
     */
    public static function guessFromExtension(string $extension): self
    {
        $extension = strtolower($extension);

        foreach (self::cases() as $case) {
            if ($case === self::ANNEX) continue; // 跳过 OTHER

            if (in_array($extension, $case->fileExt())) {
                return $case;
            }
        }

        return self::ANNEX;
    }

    /**
     * 文件扩展名
     */
    public function fileExt(): array|string
    {
        return match ($this) {
            self::IMAGE => ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'avif', 'webp', 'svg', 'ico'],
            self::AUDIO => ['mp3', 'wma', 'wav', 'ape', 'flac', 'ogg', 'aac'],
            self::VIDEO => ['mp4', 'mov', 'wmv', 'flv', 'avl', 'webm', 'mkv'],
            self::DOCUMENT => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'md', 'csv'],
            self::ZIP => ['zip', 'rar', '7z', 'tar', 'gz'],
            self::ANNEX => '*',
        };
    }
}
