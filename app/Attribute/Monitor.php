<?php


namespace App\Attribute;

use App\Models\MonitorModel;
use Attribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * 系统监控注解类
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Monitor
{
    /**
     * 系统监控注解
     *
     * @param  string  $name  接口名称
     * @param  bool  $auth  记录权限
     * @param  string  $user_id  用户ID
     */
    public function __construct(string $name = '', bool $auth = true, string $user_id = '')
    {
        if ($auth) {
            $user_id = Auth::getAdminId();
        }
        $currentRoute = Route::current();
        $action = $currentRoute->getActionName();
        $ip = request()->ip();
        $address = $this->getMethod($ip);
        $url = request()->url();
        $host = request()->host();
        $data = json_encode(request()->post(), JSON_UNESCAPED_UNICODE);
        $params = json_encode(request()->query(), JSON_UNESCAPED_UNICODE);
        $created_at = date('Y-m-d H:i:s');
        DB::table('monitor')->insert(compact('name', 'address', 'user_id', 'action', 'url', 'data', 'host', 'ip', 'params', 'created_at'));
    }

    /**
     * 获取请求IP省市县
     */
    public function getMethod($ip): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://searchplugin.csdn.net/api/v1/ip/get?ip=' . $ip);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $header[] = 'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($ch);
        curl_close($ch);
        $resData = json_decode($response, true);
        if (isset($resData['code']) && $resData['code'] == 200) {
            return $resData['data']['address'];
        } else {
            return '未知';
        }
    }
}
