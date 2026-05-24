<?php

namespace Modules\SystemTool\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\SystemTool\Settings\StorageSettings;

/**
 * 文件存储配置管理
 */
#[RequestAttribute('/system/storage', 'system.storage')]
class SysStorageController extends BaseController
{
    /**
     * 获取存储配置（从 DB 加载已保存的值，fallback 到 config 文件）
     */
    #[GetRoute('/config', 'config')]
    public function getConfig(): JsonResponse
    {
        $filesystems = config('filesystems');
        $default = $filesystems['default'] ?? 'local';

        // 获取各驱动配置
        $disks = $filesystems['disks'] ?? [];

        // 本地存储配置
        $local = [
            'root' => $disks['local']['root'] ?? storage_path('app/public'),
            'url' => $disks['local']['url'] ?? config('app.url') . '/storage',
            'visibility' => $disks['local']['visibility'] ?? 'public',
        ];

        // S3 / OSS 配置
        $s3 = [
            'key' => $disks['s3']['key'] ?? '',
            'secret' => $disks['s3']['secret'] ?? '',
            'region' => $disks['s3']['region'] ?? '',
            'bucket' => $disks['s3']['bucket'] ?? '',
            'url' => $disks['s3']['url'] ?? '',
            'endpoint' => $disks['s3']['endpoint'] ?? '',
            'use_path_style_endpoint' => $disks['s3']['use_path_style_endpoint'] ?? false,
        ];

        // FTP 配置
        $ftp = [
            'host' => $disks['ftp']['host'] ?? '',
            'username' => $disks['ftp']['username'] ?? '',
            'password' => $disks['ftp']['password'] ?? '',
            'port' => $disks['ftp']['port'] ?? 21,
            'root' => $disks['ftp']['root'] ?? '',
            'passive' => $disks['ftp']['passive'] ?? true,
            'ssl' => $disks['ftp']['ssl'] ?? false,
            'timeout' => $disks['ftp']['timeout'] ?? 30,
        ];

        // SFTP 配置
        $sftp = [
            'host' => $disks['sftp']['host'] ?? '',
            'username' => $disks['sftp']['username'] ?? '',
            'password' => $disks['sftp']['password'] ?? '',
            'port' => $disks['sftp']['port'] ?? 22,
            'root' => $disks['sftp']['root'] ?? '',
            'timeout' => $disks['sftp']['timeout'] ?? 30,
            'private_key' => $disks['sftp']['privateKey'] ?? '',
            'passphrase' => $disks['sftp']['passphrase'] ?? '',
        ];

        return $this->success([
            'default' => $default,
            'local' => $local,
            's3' => $s3,
            'ftp' => $ftp,
            'sftp' => $sftp,
        ]);
    }

    /**
     * 保存存储配置到数据库
     *
     * 使用 StorageSettings::set() 将每个配置项写入 应用配置 表，
     * 写入后自动更新缓存，并通过全局中间件同步到 config() 运行时。
     */
    #[PostRoute('/save', 'save')]
    public function saveConfig(): JsonResponse
    {
        $data = request()->all();

        $default = $data['default'] ?? 'local';
        $local = $data['local'] ?? [];
        $s3 = $data['s3'] ?? [];
        $ftp = $data['ftp'] ?? [];
        $sftp = $data['sftp'] ?? [];

        try {
            // 默认存储驱动
            StorageSettings::set('filesystems.default', $default);

            // 本地存储
            if (!empty($local['url'])) {
                StorageSettings::set('filesystems.disks.local.url', $local['url']);
            }

            // S3
            if ($default === 's3' || !empty($s3['key'])) {
                StorageSettings::set('filesystems.disks.s3.key', $s3['key'] ?? '');
                StorageSettings::set('filesystems.disks.s3.secret', $s3['secret'] ?? '');
                StorageSettings::set('filesystems.disks.s3.region', $s3['region'] ?? '');
                StorageSettings::set('filesystems.disks.s3.bucket', $s3['bucket'] ?? '');
                StorageSettings::set('filesystems.disks.s3.url', $s3['url'] ?? '');
                StorageSettings::set('filesystems.disks.s3.endpoint', $s3['endpoint'] ?? '');
                StorageSettings::set('filesystems.disks.s3.use_path_style_endpoint', (bool) ($s3['use_path_style_endpoint'] ?? false));
            }

            // FTP
            if ($default === 'ftp' || !empty($ftp['host'])) {
                StorageSettings::set('filesystems.disks.ftp.host', $ftp['host'] ?? '');
                StorageSettings::set('filesystems.disks.ftp.username', $ftp['username'] ?? '');
                StorageSettings::set('filesystems.disks.ftp.password', $ftp['password'] ?? '');
                StorageSettings::set('filesystems.disks.ftp.port', (int) ($ftp['port'] ?? 21));
                StorageSettings::set('filesystems.disks.ftp.root', $ftp['root'] ?? '');
                StorageSettings::set('filesystems.disks.ftp.passive', (bool) ($ftp['passive'] ?? true));
                StorageSettings::set('filesystems.disks.ftp.ssl', (bool) ($ftp['ssl'] ?? false));
                StorageSettings::set('filesystems.disks.ftp.timeout', (int) ($ftp['timeout'] ?? 30));
            }

            // SFTP
            if ($default === 'sftp' || !empty($sftp['host'])) {
                StorageSettings::set('filesystems.disks.sftp.host', $sftp['host'] ?? '');
                StorageSettings::set('filesystems.disks.sftp.username', $sftp['username'] ?? '');
                StorageSettings::set('filesystems.disks.sftp.password', $sftp['password'] ?? '');
                StorageSettings::set('filesystems.disks.sftp.port', (int) ($sftp['port'] ?? 22));
                StorageSettings::set('filesystems.disks.sftp.root', $sftp['root'] ?? '');
                StorageSettings::set('filesystems.disks.sftp.timeout', (int) ($sftp['timeout'] ?? 30));
                StorageSettings::set('filesystems.disks.sftp.privateKey', $sftp['private_key'] ?? '');
                StorageSettings::set('filesystems.disks.sftp.passphrase', $sftp['passphrase'] ?? '');
            }

            // 清除 Laravel 配置缓存
            Artisan::call('config:clear');

            return $this->success('保存成功');
        } catch (\Throwable $e) {
            return $this->error('保存失败：' . $e->getMessage());
        }
    }

    /**
     * 测试存储连接
     */
    #[PostRoute('/test', 'test')]
    public function testConnection(): JsonResponse
    {
        $disk = request()->input('disk', 'local');

        try {
            $storage = Storage::disk($disk);
            $testFile = 'storage_test_' . time() . '.txt';
            $testContent = 'XinAdmin 存储测试文件 - ' . date('Y-m-d H:i:s');

            // 测试写入
            $storage->put($testFile, $testContent);

            // 测试读取
            $readContent = $storage->get($testFile);
            if ($readContent !== $testContent) {
                return $this->error('读取测试失败：内容不匹配');
            }

            // 测试删除
            $storage->delete($testFile);

            return $this->success('存储连接测试成功');
        } catch (\Throwable $e) {
            return $this->error('连接测试失败: ' . $e->getMessage());
        }
    }

}
