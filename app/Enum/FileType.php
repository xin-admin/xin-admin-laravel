<?php

namespace App\Enum;

/**
 * 枚举类：文件类型
 * Class FileType
 */
enum FileType: int
{
    // 图片
    case IMAGE = 10;

    //音频
    case MP3 = 20;

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
            self::IMAGE => '图片',
            self::MP3 => '音频',
            self::VIDEO => '视频',
            self::ZIP => '压缩包',
            self::ANNEX => '附件',
        };
    }

    /**
     * 获取预览地址
     */
    public function previewPath(): string
    {
        return match ($this) {
            self::IMAGE => 'static/image.png',
            self::MP3 => 'static/mp3.png',
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
            self::MP3, self::VIDEO, self::ZIP, self::ANNEX => 10485760,
        };
    }

    /**
     * 文件扩展名
     */
    public function fileExt(): array|string
    {
        return match ($this) {
            self::IMAGE => ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'avif', 'webp'],
            self::MP3 => ['mp3', 'wma', 'wav', 'ape', 'flac', 'ogg', 'aac'],
            self::VIDEO => ['mp4', 'mov', 'wmv', 'flv', 'avl', 'webm', 'mkv'],
            self::ZIP => ['zip', 'rar'],
            self::ANNEX => '*',
        };
    }
}
