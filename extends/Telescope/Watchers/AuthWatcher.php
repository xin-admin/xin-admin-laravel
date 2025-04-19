<?php

namespace Xin\Telescope\Watchers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use Xin\Telescope\EntryType;
use Xin\Telescope\IncomingEntry;
use Xin\Telescope\Telescope;

class AuthWatcher extends Watcher
{

    /**
     * Register the watcher.
     */
    public function register($app): void
    {
        $app['events']->listen(Login::class, [$this, 'recordLogin']);
        $app['events']->listen(Failed::class, [$this, 'recordFailed']);
        $app['events']->listen(Logout::class, [$this, 'recordLogout']);
    }

    public function recordLogin(Login $event): void
    {
        if (! Telescope::isRecording()) {
            return;
        }
        $array = [
            'remember' => $event->remember,
            'user_type' => $event->guard,
            'type' => 'login',
        ];
        $this->record($array, $event->user);
    }

    public function recordFailed(Failed $event): void
    {
        if (! Telescope::isRecording()) {
            return;
        }

        $hidden = $this->options['ignore_login_credentials'] ?? [];

        $credentials = $this->hideCredentials($event->credentials, $hidden);

        $array = [
            'credentials' => $credentials,
            'user_type' => $event->guard,
            'type' => 'loginFailed',
        ];
        $this->record($array, $event->user);
    }

    public function recordLogout(Logout $event): void
    {
        if (! Telescope::isRecording()) {
            return;
        }
        $array = [
            'user_type' => $event->guard,
            'type' => 'logout',
        ];

        $this->record($array, Auth::user());
    }

    public function record(array $array, Authenticatable $user): void
    {
        $userAgent = Request::userAgent();

        $entry = IncomingEntry::make(array_merge($array, [
            'ipaddr' => implode(',', Request::ips()),
            'browser' => $this->getBrowser($userAgent),
            'os' => $this->getOs($userAgent),
            'login_location' => $this->getLocation(Request::ips()),
            'login_time' => date('Y-m-d H:i:s'),
        ]), EntryType::AUTH);

        $entry->user($user);

        Telescope::record($entry);
    }

    /**
     * Hide the given parameters.
     */
    protected function hideCredentials(array $data, array $hidden): array
    {
        foreach ($hidden as $parameter) {
            if (Arr::get($data, $parameter)) {
                Arr::set($data, $parameter, '********');
            }
        }

        return $data;
    }

    /**
     * Get browser info
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
     * Get os info
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
     */
    private function getLocation(array | string $ip): string
    {
        if(is_string($ip) && $ip == '127.0.0.1') {
            return '本地';
        }
        // 这里可以使用第三方 API（如 IPStack 或 IPInfo）来获取地理位置
        try {
            if(is_string($ip)) {
                $response = file_get_contents("https://ipinfo.io/{$ip}/json");
                $data = json_decode($response, true);
                return $data['city'] . ', ' . $data['country'];
            } else {
                $city = '';
                for ($i = 0; $i < count($ip); $i++) {
                    $response = file_get_contents("https://ipinfo.io/{$ip[$i]}/json");
                    $data = json_decode($response, true);
                    $city .= $data['city'] . ', ' . $data['country'];
                }
                return $city;
            }
        }catch (\Exception $e) {
            return 'XXX';
        }
    }
}