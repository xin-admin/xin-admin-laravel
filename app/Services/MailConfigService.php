<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * 邮件配置服务
 * 从系统设置中获取邮件配置并动态配置 Laravel 邮件
 */
class MailConfigService
{
    /**
     * 从系统设置初始化邮件配置
     * @return bool
     */
    public static function initFromSettings(): bool
    {
        try {
            $mailSettings = SysSettingService::getSetting('mail');
            
            // 如果没有邮件配置，使用默认配置
            if (empty($mailSettings)) {
                Log::debug('邮件配置未设置，使用默认配置');
                return false;
            }
            
            // 获取邮件驱动类型
            $mailer = $mailSettings['mailer'] ?? 'smtp';
            
            // 设置默认邮件驱动
            Config::set('mail.default', $mailer);
            
            // 配置 SMTP 相关设置
            if ($mailer === 'smtp') {
                self::configureSmtp($mailSettings);
            }
            
            // 配置发件人信息
            self::configureFrom($mailSettings);
            
            Log::debug('邮件配置已从系统设置加载', ['mailer' => $mailer]);
            return true;
        } catch (\Throwable $e) {
            Log::error('初始化邮件配置失败', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * 配置 SMTP 设置
     * @param array $settings
     */
    private static function configureSmtp(array $settings): void
    {
        $host = $settings['host'] ?? '';
        $port = $settings['port'] ?? 465;
        $username = $settings['username'] ?? '';
        $password = $settings['password'] ?? '';
        $encryption = $settings['encryption'] ?? 'ssl';
        
        // 设置 SMTP 配置
        Config::set('mail.mailers.smtp.host', $host);
        Config::set('mail.mailers.smtp.port', (int)$port);
        Config::set('mail.mailers.smtp.username', $username);
        Config::set('mail.mailers.smtp.password', $password);
        
        // 处理加密方式
        // Laravel 使用 scheme 而非 encryption，支持的值为：'smtp', 'smtps'
        // ssl -> smtps, tls -> smtp, null/'' -> null
        $scheme = match($encryption) {
            'ssl' => 'smtps',      // SSL 加密对应 smtps
            'tls' => 'smtp',       // TLS 加密对应 smtp
            'null' => null,        // 前端选项 'null' 表示无加密
            '' => null,            // 空值表示无加密
            default => null,
        };
        Config::set('mail.mailers.smtp.scheme', $scheme);
    }

    /**
     * 配置发件人信息
     * @param array $settings
     */
    private static function configureFrom(array $settings): void
    {
        $fromAddress = $settings['from_address'] ?? '';
        $fromName = $settings['from_name'] ?? 'Xin Admin';
        
        if (!empty($fromAddress)) {
            Config::set('mail.from.address', $fromAddress);
        }
        
        if (!empty($fromName)) {
            Config::set('mail.from.name', $fromName);
        }
    }

    /**
     * 获取当前邮件配置
     * @return array
     */
    public static function getMailConfig(): array
    {
        return [
            'default' => Config::get('mail.default'),
            'smtp' => Config::get('mail.mailers.smtp'),
            'from' => Config::get('mail.from'),
        ];
    }

    /**
     * 检查邮件配置是否有效
     * @return bool
     */
    public static function isConfigured(): bool
    {
        $mailSettings = SysSettingService::getSetting('mail');
        
        if (empty($mailSettings)) {
            return false;
        }
        
        // 检查必要的 SMTP 配置
        $mailer = $mailSettings['mailer'] ?? 'smtp';
        
        if ($mailer === 'smtp') {
            return !empty($mailSettings['host']) 
                && !empty($mailSettings['username']) 
                && !empty($mailSettings['password']);
        }
        
        return true;
    }

    /**
     * 刷新邮件配置（当系统设置更新后调用）
     * @return bool
     */
    public static function refresh(): bool
    {
        return self::initFromSettings();
    }
}
