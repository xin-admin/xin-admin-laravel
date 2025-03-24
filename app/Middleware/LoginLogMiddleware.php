<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AdminLoginLogModel;

class LoginLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 获取用户名（假设用户名通过请求体传递）
        $username = $request->input('username', '');
        $userAgent = $request->userAgent();
        // 继续处理请求
        $response = $next($request);
        try {
            // 获取响应状态和消息
            $status = $response->getStatusCode(); // HTTP 状态码
            $content = json_decode($response->getContent(), true); // 响应内容
            $message = $content['msg'] ?? 'No message'; // 从响应中提取消息
            AdminLoginLogModel::create([
                'ipaddr' => $request->ip(),
                'browser' => $this->getBrowser($userAgent),
                'os' => $this->getOs($userAgent),
                'username' => $username,
                'login_location' => $this->getLocation($request->ip()),
                'status' => $status === 200 ? '0' : '1',
                'msg' => $message,
                'login_time' => date('Y-m-d H:i:s'),
            ]);
        }catch (\Exception $e) {
            // 记录错误日志
            Log::error('Failed to log user login info: ' . $e->getMessage());
        }
        return $response;
    }

    /**
     * 获取浏览器信息
     * @param string $userAgent
     * @return string
     */
    private function getBrowser(string $userAgent): string
    {
        $browser = 'XXX';
        // 简单的解析逻辑（可以根据需要扩展）
        if (str_contains($userAgent, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($userAgent, 'Chrome')) {
            $browser = 'Chrome';
        } elseif (str_contains($userAgent, 'Safari')) {
            $browser = 'Safari';
        } elseif (str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident')) {
            $browser = 'Internet Explorer';
        }
        return $browser;
    }

    /**
     * 获取操作系统信息
     * @param string $userAgent
     * @return string
     */
    private function getOs(string $userAgent): string
    {
        $os = 'Unknown';
        if (str_contains($userAgent, 'Windows')) {
            $os = 'Windows';
        } elseif (str_contains($userAgent, 'Macintosh')) {
            $os = 'Mac OS';
        } elseif (str_contains($userAgent, 'Linux')) {
            $os = 'Linux';
        } elseif (str_contains($userAgent, 'Android')) {
            $os = 'Android';
        } elseif (str_contains($userAgent, 'iOS')) {
            $os = 'iOS';
        }
        return $os;
    }

    /**
     * 获取 IP 地址对应的地理位置
     *
     * @param string $ip
     * @return string
     */
    private function getLocation(string $ip): string
    {
        if($ip == '127.0.0.1') {
            return '本地';
        }
        // 这里可以使用第三方 API（如 IPStack 或 IPInfo）来获取地理位置
        try {
            $response = file_get_contents("https://ipinfo.io/{$ip}/json");
            $data = json_decode($response, true);
            return $data['city'] . ', ' . $data['country'];
        }catch (\Exception $e) {
            return 'XXX';
        }
    }
}
