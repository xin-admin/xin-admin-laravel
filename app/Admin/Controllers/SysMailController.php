<?php

namespace App\Admin\Controllers;

use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use App\Common\Services\AnnoRoute\Route\PostRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

/**
 * 邮件配置管理
 */
#[RequestAttribute('/system/mail', 'system.mail')]
class SysMailController extends BaseController
{
    /**
     * 获取邮件配置
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
     * 保存邮件配置
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

        $envData = [];

        if ($mode === 'single') {
            $envData['MAIL_MAILER'] = $mail['default'] ?? 'smtp';
        } elseif ($mode === 'failover') {
            $envData['MAIL_MAILER'] = 'failover';
            $envData['MAIL_FAILOVER_MAILERS'] = implode(',', $selectedMailers);
        } elseif ($mode === 'roundrobin') {
            $envData['MAIL_MAILER'] = 'roundrobin';
            $envData['MAIL_ROUNDROBIN_MAILERS'] = implode(',', $selectedMailers);
        }

        // 处理 SMTP 配置
        if (isset($mail['mailers']['smtp'])) {
            $smtp = $mail['mailers']['smtp'];
            $envData['MAIL_HOST'] = $smtp['host'] ?? '127.0.0.1';
            $envData['MAIL_PORT'] = $smtp['port'] ?? 587;
            $envData['MAIL_USERNAME'] = $smtp['username'] ?? '';
            $envData['MAIL_PASSWORD'] = $smtp['password'] ?? '';
        }

        // 处理发件人配置
        if (isset($mail['from'])) {
            $envData['MAIL_FROM_ADDRESS'] = $mail['from']['address'] ?? '';
            $envData['MAIL_FROM_NAME'] = $mail['from']['name'] ?? '';
        }

        // 处理日志驱动配置
        if (isset($mail['mailers']['log'])) {
            $envData['MAIL_LOG_CHANNEL'] = $mail['mailers']['log']['channel'] ?? 'stack';
        }

        // 处理第三方服务配置
        if (isset($services['postmark'])) {
            $envData['POSTMARK_TOKEN'] = $services['postmark']['token'] ?? '';
        }
        if (isset($services['ses'])) {
            $envData['AWS_ACCESS_KEY_ID'] = $services['ses']['key'] ?? '';
            $envData['AWS_SECRET_ACCESS_KEY'] = $services['ses']['secret'] ?? '';
            $envData['AWS_DEFAULT_REGION'] = $services['ses']['region'] ?? 'us-east-1';
            $envData['AWS_SESSION_TOKEN'] = $services['ses']['token'] ?? '';
        }
        if (isset($services['resend'])) {
            $envData['RESEND_KEY'] = $services['resend']['key'] ?? '';
        }
        if (isset($services['mailgun'])) {
            $envData['MAILGUN_DOMAIN'] = $services['mailgun']['domain'] ?? '';
            $envData['MAILGUN_SECRET'] = $services['mailgun']['secret'] ?? '';
            $envData['MAILGUN_ENDPOINT'] = $services['mailgun']['endpoint'] ?? 'api.mailgun.net';
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
