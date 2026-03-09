<?php

namespace App\Admin\Controllers;

use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use App\Common\Services\AnnoRoute\Route\PostRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/**
 * 文件存储配置管理
 */
#[RequestAttribute('/system/storage', 'system.storage')]
class SysStorageController extends BaseController
{
    /**
     * 获取存储配置
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
            'url' => $disks['local']['url'] ?? env('APP_URL') . '/storage',
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
     * 保存存储配置
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

        $envData = [];

        // 设置默认存储驱动
        $envData['FILESYSTEM_DISK'] = $default;

        // 本地存储配置
        if (!empty($local['url'])) {
            $envData['FILESYSTEM_LOCAL_URL'] = $local['url'];
        }

        // S3 / OSS 配置
        if ($default === 's3' || !empty($s3['key'])) {
            $envData['AWS_ACCESS_KEY_ID'] = $s3['key'] ?? '';
            $envData['AWS_SECRET_ACCESS_KEY'] = $s3['secret'] ?? '';
            $envData['AWS_DEFAULT_REGION'] = $s3['region'] ?? '';
            $envData['AWS_BUCKET'] = $s3['bucket'] ?? '';
            $envData['AWS_URL'] = $s3['url'] ?? '';
            $envData['AWS_ENDPOINT'] = $s3['endpoint'] ?? '';
            $envData['AWS_USE_PATH_STYLE_ENDPOINT'] = $s3['use_path_style_endpoint'] ? 'true' : 'false';
        }

        // FTP 配置
        if ($default === 'ftp' || !empty($ftp['host'])) {
            $envData['FTP_HOST'] = $ftp['host'] ?? '';
            $envData['FTP_USERNAME'] = $ftp['username'] ?? '';
            $envData['FTP_PASSWORD'] = $ftp['password'] ?? '';
            $envData['FTP_PORT'] = $ftp['port'] ?? 21;
            $envData['FTP_ROOT'] = $ftp['root'] ?? '';
            $envData['FTP_PASSIVE'] = ($ftp['passive'] ?? true) ? 'true' : 'false';
            $envData['FTP_SSL'] = ($ftp['ssl'] ?? false) ? 'true' : 'false';
            $envData['FTP_TIMEOUT'] = $ftp['timeout'] ?? 30;
        }

        // SFTP 配置
        if ($default === 'sftp' || !empty($sftp['host'])) {
            $envData['SFTP_HOST'] = $sftp['host'] ?? '';
            $envData['SFTP_USERNAME'] = $sftp['username'] ?? '';
            $envData['SFTP_PASSWORD'] = $sftp['password'] ?? '';
            $envData['SFTP_PORT'] = $sftp['port'] ?? 22;
            $envData['SFTP_ROOT'] = $sftp['root'] ?? '';
            $envData['SFTP_TIMEOUT'] = $sftp['timeout'] ?? 30;
            $envData['SFTP_PRIVATE_KEY'] = $sftp['private_key'] ?? '';
            $envData['SFTP_PASSPHRASE'] = $sftp['passphrase'] ?? '';
        }

        try {
            $this->updateEnv($envData);
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

    /**
     * 更新 .env 文件
     * @param array $data
     * @return void
     */
    protected function updateEnv(array $data): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }
        $content = file_get_contents($envPath);
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                $value = '';
            }
            $value = (string) $value;
            if (str_contains($value, ' ') && !str_contains($value, '"')) {
                $value = '"' . $value . '"';
            }
            $pattern = "/^{$key}=.*/m";

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}={$value}", $content);
            } else {
                if (!str_ends_with($content, "\n")) {
                    $content .= "\n";
                }
                $content .= "{$key}={$value}\n";
            }
        }
        file_put_contents($envPath, $content);
    }
}
