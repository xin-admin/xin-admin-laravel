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

    public static function image(): int
    {
        return self::IMAGE->value;
    }

    public static function mp3(): int
    {
        return self::MP3->value;
    }

    public static function video(): int
    {
        return self::VIDEO->value;
    }

    public static function zip(): int
    {
        return self::ZIP->value;
    }

    public static function annex(): int
    {
        return self::ANNEX->value;
    }

    /**
     * 获取枚举类型值
     */
    public static function data(): array
    {
        return [
            self::IMAGE->value => [
                'name' => '图片', // 类型名称
                'value' => self::IMAGE, // 类型值
                'preview_path' => '', // 预览地址
                'max_size' => 2097152, // 上传最大大小
                'file_ext' => ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'avif', 'webp'], // 文件扩展名
            ],
            self::MP3->value => [
                'name' => '音频',
                'value' => self::MP3,
                'preview_path' => 'static/mp3.png',
                'max_size' => 10485760,
                'file_ext' => ['mp3', 'wma', 'wav', 'ape', 'flac', 'ogg', 'aac'],
            ],
            self::VIDEO->value => [
                'name' => '视频',
                'value' => self::VIDEO,
                'preview_path' => 'static/video.png',
                'max_size' => 10485760,
                'file_ext' => ['mp4', 'mov', 'wmv', 'flv', 'avl', 'webm', 'mkv'],
            ],
            self::ZIP->value => [
                'name' => '压缩包',
                'value' => self::ZIP,
                'preview_path' => 'static/zip.png',
                'max_size' => 10485760,
                'file_ext' => ['zip', 'rar'],
            ],
            self::ANNEX->value => [
                'name' => '附件',
                'value' => self::ANNEX,
                'preview_path' => 'static/annex.png',
                'max_size' => 10485760,
                'file_ext' => '*',
            ],
        ];
    }
}
