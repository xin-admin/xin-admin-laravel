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

    //音频
    case AUDIO = 20;

    // 视频
    case VIDEO = 30;

    // 压缩包
    case ZIP = 40;

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
            self::ANNEX => __('system.file.annex'),
        };
    }

    /**
     * 获取预览地址
     */
    public function previewPath(): string
    {
        return match ($this) {
            self::IMAGE => 'static/image.png',
            self::AUDIO => 'static/audio.png',
            self::VIDEO => 'static/video.png',
            self::ZIP => 'static/zip.png',
            self::ANNEX => 'static/annex.png',
        };
    }

    /**
     * 获取最大大小
     */
    public function maxSize(): int
    {
        return match ($this) {
            self::IMAGE => 2097152,
            self::AUDIO, self::VIDEO, self::ZIP, self::ANNEX => 10485760,
        };
    }

    /**
     * 文件扩展名
     */
    public function fileExt(): array|string
    {
        return match ($this) {
            self::IMAGE => ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'avif', 'webp'],
            self::AUDIO => ['mp3', 'wma', 'wav', 'ape', 'flac', 'ogg', 'aac'],
            self::VIDEO => ['mp4', 'mov', 'wmv', 'flv', 'avl', 'webm', 'mkv'],
            self::ZIP => ['zip', 'rar'],
            self::ANNEX => '*',
        };
    }
}
