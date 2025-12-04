<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * 存储配置服务
 * 从系统设置中获取文件存储配置并动态配置 Laravel Filesystem
 */
class StorageConfigService
{
    /**
     * 从系统设置初始化存储配置
     * @return bool
     */
    public static function initFromSettings(): bool
    {
        try {
            $storageSettings = SysSettingService::getSetting('storage');
            
            // 如果没有存储配置，使用默认配置
            if (empty($storageSettings)) {
                Log::debug('存储配置未设置，使用默认配置');
                return false;
            }
            
            // 获取存储驱动类型
            $disk = $storageSettings['disk'] ?? 'public';
            
            // 设置默认存储驱动
            Config::set('filesystems.default', $disk);
            
            // 如果是 S3 驱动，配置 S3 相关设置
            if ($disk === 's3') {
                self::configureS3($storageSettings);
            }
            
            Log::debug('存储配置已从系统设置加载', ['disk' => $disk]);
            return true;
        } catch (\Throwable $e) {
            Log::error('初始化存储配置失败', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * 配置 S3 设置
     * @param array $settings
     */
    private static function configureS3(array $settings): void
    {
        $key = $settings['s3_key'] ?? '';
        $secret = $settings['s3_secret'] ?? '';
        $region = $settings['s3_region'] ?? '';
        $bucket = $settings['s3_bucket'] ?? '';
        $url = $settings['s3_url'] ?? '';
        $endpoint = $settings['s3_endpoint'] ?? '';
        
        // 设置 S3 配置
        Config::set('filesystems.disks.s3.key', $key);
        Config::set('filesystems.disks.s3.secret', $secret);
        Config::set('filesystems.disks.s3.region', $region);
        Config::set('filesystems.disks.s3.bucket', $bucket);
        
        // 设置访问 URL（用于生成文件访问链接）
        if (!empty($url)) {
            Config::set('filesystems.disks.s3.url', $url);
        }
        
        // 设置端点（用于 S3 兼容存储，如阿里云OSS、MinIO等）
        if (!empty($endpoint)) {
            Config::set('filesystems.disks.s3.endpoint', $endpoint);
            // 使用路径风格端点（兼容更多 S3 兼容存储）
            Config::set('filesystems.disks.s3.use_path_style_endpoint', true);
        }
    }

    /**
     * 获取当前存储配置
     * @return array
     */
    public static function getStorageConfig(): array
    {
        return [
            'default' => Config::get('filesystems.default'),
            'disks' => [
                'local' => Config::get('filesystems.disks.local'),
                'public' => Config::get('filesystems.disks.public'),
                's3' => Config::get('filesystems.disks.s3'),
            ],
        ];
    }

    /**
     * 检查存储配置是否有效
     * @return bool
     */
    public static function isConfigured(): bool
    {
        $storageSettings = SysSettingService::getSetting('storage');
        
        if (empty($storageSettings)) {
            return false;
        }
        
        $disk = $storageSettings['disk'] ?? 'public';
        
        // 本地存储始终可用
        if ($disk === 'local' || $disk === 'public') {
            return true;
        }
        
        // S3 需要检查必要配置
        if ($disk === 's3') {
            return !empty($storageSettings['s3_key']) 
                && !empty($storageSettings['s3_secret']) 
                && !empty($storageSettings['s3_bucket'])
                && !empty($storageSettings['s3_region']);
        }
        
        return false;
    }

    /**
     * 检查 S3 配置是否有效
     * @return bool
     */
    public static function isS3Configured(): bool
    {
        $storageSettings = SysSettingService::getSetting('storage');
        
        if (empty($storageSettings)) {
            return false;
        }
        
        return !empty($storageSettings['s3_key']) 
            && !empty($storageSettings['s3_secret']) 
            && !empty($storageSettings['s3_bucket'])
            && !empty($storageSettings['s3_region']);
    }

    /**
     * 获取当前默认磁盘名称
     * @return string
     */
    public static function getDefaultDisk(): string
    {
        return Config::get('filesystems.default', 'public');
    }

    /**
     * 刷新存储配置（当系统设置更新后调用）
     * @return bool
     */
    public static function refresh(): bool
    {
        return self::initFromSettings();
    }
}
