<?php

namespace Modules\SystemTool\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\SystemTool\Settings\MailSettings;

/**
 * 邮件配置管理
 */
#[RequestAttribute('/system/mail', 'system.mail')]
class SysMailController extends BaseController
{
    /**
     * 获取邮件配置（从 DB 加载已保存的值，fallback 到 config 文件）
     */
    #[GetRoute('/config', 'config')]
    public function getConfig(): JsonResponse
    {
        $mail = config('mail');
        $mode = $mail['default'] ?? 'single';
        $mailers = [];
        if($mode === 'failover') {
            $mailers = $mail['mailers']['failover']['mailers'] ?? [];
        } elseif ($mode === 'roundrobin') {
            $mailers = $mail['mailers']['roundrobin']['mailers'] ?? [];
        }
        $other = [
            'mode' => 'single',
            'mailers' => $mailers
        ];
        if($mode === 'failover' || $mode == 'roundrobin') {
            $other['mode'] = $mode;
            $mail['default'] = $mailers[0] ?? 'smtp';
        }
        return $this->success([
            'other' => $other,
            'mail' => $mail,
            'services' => config('services')
        ]);
    }

    /**
     * 保存邮件配置到数据库
     *
     * 使用 MailSettings::set() 将每个配置项写入 应用配置 表，
     * 写入后自动更新缓存，并通过全局中间件同步到 config() 运行时。
     */
    #[PostRoute('/save', 'save')]
    public function saveConfig(): JsonResponse
    {
        $data = request()->all();

        // 解析前端提交的数据结构
        $other = $data['other'] ?? [];
        $mail = $data['mail'] ?? [];
        $services = $data['services'] ?? [];

        // 确定模式并构建 mailers 配置
        $mode = $other['mode'] ?? 'single';
        $selectedMailers = $other['mailers'] ?? [];

        try {
            // 邮件默认驱动
            if ($mode === 'single') {
                MailSettings::set('mail.default', $mail['default'] ?? 'smtp');
            } elseif ($mode === 'failover') {
                MailSettings::set('mail.default', 'failover');
                MailSettings::set('mail.mailers.failover.mailers', $selectedMailers);
            } elseif ($mode === 'roundrobin') {
                MailSettings::set('mail.default', 'roundrobin');
                MailSettings::set('mail.mailers.roundrobin.mailers', $selectedMailers);
            }

            // SMTP 配置
            if (isset($mail['mailers']['smtp'])) {
                $smtp = $mail['mailers']['smtp'];
                MailSettings::set('mail.mailers.smtp.host', $smtp['host'] ?? '127.0.0.1');
                MailSettings::set('mail.mailers.smtp.port', (int) ($smtp['port'] ?? 587));
                MailSettings::set('mail.mailers.smtp.username', $smtp['username'] ?? '');
                MailSettings::set('mail.mailers.smtp.password', $smtp['password'] ?? '');
            }

            // 发件人配置
            if (isset($mail['from'])) {
                MailSettings::set('mail.from.address', $mail['from']['address'] ?? '');
                MailSettings::set('mail.from.name', $mail['from']['name'] ?? '');
            }

            // 日志驱动配置
            if (isset($mail['mailers']['log'])) {
                MailSettings::set('mail.mailers.log.channel', $mail['mailers']['log']['channel'] ?? 'stack');
            }

            // 第三方服务配置
            if (isset($services['postmark'])) {
                MailSettings::set('services.postmark.token', $services['postmark']['token'] ?? '');
            }
            if (isset($services['ses'])) {
                MailSettings::set('services.ses.key', $services['ses']['key'] ?? '');
                MailSettings::set('services.ses.secret', $services['ses']['secret'] ?? '');
                MailSettings::set('services.ses.region', $services['ses']['region'] ?? 'us-east-1');
                MailSettings::set('services.ses.token', $services['ses']['token'] ?? '');
            }
            if (isset($services['resend'])) {
                MailSettings::set('services.resend.key', $services['resend']['key'] ?? '');
            }
            if (isset($services['mailgun'])) {
                MailSettings::set('services.mailgun.domain', $services['mailgun']['domain'] ?? '');
                MailSettings::set('services.mailgun.secret', $services['mailgun']['secret'] ?? '');
                MailSettings::set('services.mailgun.endpoint', $services['mailgun']['endpoint'] ?? 'api.mailgun.net');
            }

            // 清除 Laravel 配置缓存
            Artisan::call('config:clear');

            return $this->success('保存成功');
        } catch (\Throwable $e) {
            return $this->error('保存失败：' . $e->getMessage());
        }
    }

    /**
     * 发送测试邮件
     */
    #[PostRoute('/test', 'test')]
    public function sendTest(): JsonResponse
    {
        $to = request()->input('to');
        if (empty($to)) {
            return $this->error('请输入收件人邮箱');
        }
        try {
            Mail::raw('这是一封来自 Xin Admin 的测试邮件，用于验证邮件服务配置是否正确。', function ($message) use ($to) {
                $message->to($to)
                    ->subject('Xin Admin 邮件配置测试');
            });
            return $this->success('测试邮件发送成功');
        } catch (\Throwable $e) {
            return $this->error('发送失败: ' . $e->getMessage());
        }
    }

}
