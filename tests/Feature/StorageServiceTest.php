<?php

namespace Tests\Feature;

use App\Services\StorageConfigService;
use App\Services\SysSettingService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageServiceTest extends TestCase
{
    /**
     * 测试存储配置服务是否正常加载配置
     */
    public function test_storage_config_service_loads_settings(): void
    {
        // 初始化存储配置
        $result = StorageConfigService::initFromSettings();
        
        // 获取当前存储配置
        $config = StorageConfigService::getStorageConfig();
        
        // 验证配置结构
        $this->assertArrayHasKey('default', $config);
        $this->assertArrayHasKey('disks', $config);
        $this->assertArrayHasKey('local', $config['disks']);
        $this->assertArrayHasKey('public', $config['disks']);
        $this->assertArrayHasKey('s3', $config['disks']);
    }

    /**
     * 测试检查存储配置是否有效
     */
    public function test_storage_config_is_configured(): void
    {
        $isConfigured = StorageConfigService::isConfigured();
        
        // 本地存储始终可用
        $this->assertIsBool($isConfigured);
    }

    /**
     * 测试获取默认磁盘
     */
    public function test_get_default_disk(): void
    {
        $disk = StorageConfigService::getDefaultDisk();
        
        // 验证返回的是有效的磁盘名称
        $this->assertIsString($disk);
        $this->assertContains($disk, ['local', 'public', 's3']);
    }

    /**
     * 测试本地存储上传（使用 Fake）
     */
    public function test_local_storage_upload(): void
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        
        // 存储文件
        $path = $file->store('file', 'public');
        
        // 验证文件已存储
        Storage::disk('public')->assertExists($path);
    }

    /**
     * 测试本地存储删除（使用 Fake）
     */
    public function test_local_storage_delete(): void
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->image('delete-test.jpg', 100, 100);
        $path = $file->store('file', 'public');
        
        // 验证文件存在
        Storage::disk('public')->assertExists($path);
        
        // 删除文件
        Storage::disk('public')->delete($path);
        
        // 验证文件已删除
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * 测试检查S3配置是否有效
     */
    public function test_s3_config_check(): void
    {
        $isS3Configured = StorageConfigService::isS3Configured();
        
        $this->assertIsBool($isS3Configured);
    }

    /**
     * 测试S3配置加载
     */
    public function test_s3_config_loads_from_settings(): void
    {
        // 初始化存储配置
        StorageConfigService::initFromSettings();
        
        // 获取S3配置
        $s3Config = Config::get('filesystems.disks.s3');
        
        // 验证S3配置结构存在
        $this->assertIsArray($s3Config);
        $this->assertArrayHasKey('driver', $s3Config);
        $this->assertEquals('s3', $s3Config['driver']);
    }

    /**
     * 测试刷新存储配置
     */
    public function test_storage_config_refresh(): void
    {
        $result = StorageConfigService::refresh();
        
        $this->assertIsBool($result);
    }

    /**
     * 测试不同文件类型的存储（使用 Fake）
     */
    public function test_different_file_types_storage(): void
    {
        Storage::fake('public');
        
        // 测试图片
        $image = UploadedFile::fake()->image('photo.jpg', 640, 480);
        $imagePath = $image->store('images', 'public');
        Storage::disk('public')->assertExists($imagePath);
        
        // 测试文档
        $doc = UploadedFile::fake()->create('document.pdf', 1024);
        $docPath = $doc->store('documents', 'public');
        Storage::disk('public')->assertExists($docPath);
        
        // 测试视频（模拟）
        $video = UploadedFile::fake()->create('video.mp4', 5120);
        $videoPath = $video->store('videos', 'public');
        Storage::disk('public')->assertExists($videoPath);
    }

    /**
     * 测试获取文件URL
     */
    public function test_get_file_url(): void
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->image('url-test.jpg');
        $path = $file->store('file', 'public');
        
        // 获取URL
        $url = Storage::disk('public')->url($path);
        
        // 验证URL是字符串
        $this->assertIsString($url);
        $this->assertStringContainsString($path, $url);
    }

    /**
     * 真实S3存储测试（需要配置正确的S3服务）
     * 运行命令: php artisan test --filter=test_real_s3_upload
     * 
     * 注意：运行此测试前请确保：
     * 1. 已在系统设置中配置正确的S3参数 (s3_key, s3_secret, s3_region, s3_bucket, s3_endpoint)
     * 2. S3服务可访问
     * 3. 已安装 league/flysystem-aws-s3-v3 扩展包
     */
    public function test_real_s3_upload(): void
    {
        // 如果S3未配置，跳过此测试
        if (!StorageConfigService::isS3Configured()) {
            $this->markTestSkipped('S3存储未配置，跳过真实上传测试');
        }
        
        // 初始化存储配置
        StorageConfigService::initFromSettings();
        
        // 获取当前S3配置用于调试
        $s3Config = Config::get('filesystems.disks.s3');
        
        // 检查必要配置
        if (empty($s3Config['key']) || empty($s3Config['secret']) || empty($s3Config['bucket'])) {
            $this->markTestSkipped('S3配置不完整: key=' . ($s3Config['key'] ?? 'null') . ', bucket=' . ($s3Config['bucket'] ?? 'null'));
        }
        
        try {
            $file = UploadedFile::fake()->image('s3-test.jpg', 100, 100);
            $path = $file->store('test', 's3');
            
            if ($path === false) {
                $this->fail('S3上传返回false，请检查S3配置: endpoint=' . ($s3Config['endpoint'] ?? 'null') . ', region=' . ($s3Config['region'] ?? 'null'));
            }
            
            $this->assertNotEmpty($path, 'S3上传返回空路径');
            
            // 验证文件存在
            $exists = Storage::disk('s3')->exists($path);
            $this->assertTrue($exists, 'S3文件上传成功');

            // 设置文件可见性
            $visibility = Storage::setVisibility($path, 'public');
            $this->assertTrue($visibility, 'S3上传测试完成');

            // 获取文件URL
            $url = Storage::disk('s3')->url($path);
            $this->assertNotEmpty($url, "获取S3文件URL成功: {$url}");
            Log::info("获取S3文件URL成功: {$url}");
            
            // 清理测试文件
//            Storage::disk('s3')->delete($path);
            
            $this->assertTrue(true, 'S3上传测试完成');
            
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            $this->fail('S3上传失败: ' . $e->getMessage() . "\n配置信息: endpoint=" . ($s3Config['endpoint'] ?? 'null'));

        }
    }
}
