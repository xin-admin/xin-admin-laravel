<?php

namespace Tests\Feature;

use App\Services\MailConfigService;
use App\Services\SysSettingService;
use App\Support\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailServiceTest extends TestCase
{
    /**
     * 测试邮件配置服务是否正常加载配置
     */
    public function test_mail_config_service_loads_settings(): void
    {
        // 初始化邮件配置
        $result = MailConfigService::initFromSettings();
        
        // 获取当前邮件配置
        $config = MailConfigService::getMailConfig();
        
        // 验证配置结构
        $this->assertArrayHasKey('default', $config);
        $this->assertArrayHasKey('smtp', $config);
        $this->assertArrayHasKey('from', $config);
    }

    /**
     * 测试检查邮件配置是否有效
     */
    public function test_mail_config_is_configured(): void
    {
        $isConfigured = MailConfigService::isConfigured();
        
        // 根据系统设置中是否配置了邮件返回结果
        $this->assertIsBool($isConfigured);
    }

    /**
     * 测试验证码邮件类创建
     */
    public function test_verification_code_mail_can_be_created(): void
    {
        $code = '123456';
        $expireMinutes = 30;
        
        $mail = new VerificationCodeMail($code, $expireMinutes);
        
        $this->assertEquals($code, $mail->code);
        $this->assertEquals($expireMinutes, $mail->expireMinutes);
    }

    /**
     * 测试邮件发送（使用 Fake）
     */
    public function test_verification_code_mail_can_be_sent(): void
    {
        Mail::fake();
        
        $testEmail = 'test@example.com';
        $code = '654321';
        
        // 发送邮件
        Mail::to($testEmail)->send(new VerificationCodeMail($code));
        
        // 验证邮件已发送
        Mail::assertSent(VerificationCodeMail::class, function ($mail) use ($code, $testEmail) {
            return $mail->code === $code 
                && $mail->hasTo($testEmail);
        });
    }

    /**
     * 测试邮件发送队列（使用 Fake）
     */
    public function test_verification_code_mail_can_be_queued(): void
    {
        Mail::fake();
        
        $testEmail = 'queue@example.com';
        $code = '789012';
        
        // 队列发送邮件
        Mail::to($testEmail)->queue(new VerificationCodeMail($code));
        
        // 验证邮件已加入队列
        Mail::assertQueued(VerificationCodeMail::class, function ($mail) use ($code) {
            return $mail->code === $code;
        });
    }

    /**
     * 真实发送测试邮件（需要配置正确的邮件服务）
     * 运行命令: php artisan test --filter=test_send_real_email
     * 
     * 注意：运行此测试前请确保：
     * 1. 已在系统设置中配置正确的邮件参数 (host, port, username, password, encryption, from_address)
     * 2. 修改下方的 TEST_MAIL_TO 或在 .env 中设置为真实邮箱地址
     * 3. 创议使用 SSL 加密 (port: 465, encryption: ssl)
     */
    public function test_send_real_email(): void
    {
        // 如果邮件未配置，跳过此测试
        if (!MailConfigService::isConfigured()) {
            $this->markTestSkipped('邮件服务未配置，跳过真实发送测试');
        }
        
        // 初始化邮件配置
        MailConfigService::initFromSettings();
        
        // 修改为真实的测试邮箱
        $testEmail = env('TEST_MAIL_TO', 'test@example.com');
        
        // 如果是默认邮箱，跳过测试
        if ($testEmail === 'test@example.com') {
            $this->markTestSkipped('请设置 TEST_MAIL_TO 环境变量为真实邮箱地址');
        }
        
        $code = sprintf('%06d', random_int(0, 999999));
        
        try {
            Mail::to($testEmail)->send(new VerificationCodeMail($code, 5));
            $this->assertTrue(true, '邮件发送成功');
        } catch (\Exception $e) {
            $this->fail('邮件发送失败: ' . $e->getMessage());
        }
    }
}
