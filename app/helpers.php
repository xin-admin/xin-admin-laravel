<?php

use App\Service\SettingService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

if (! function_exists('setting')) {
    /**
     * 获取站点的系统配置，不传递参数则获取所有配置项，配置项不存在返回 null
     *
     * @param  string  $name  变量名
     * @param mixed|null $default  默认值
     */
    function setting(string $name, mixed $default = null): mixed
    {
        return SettingService::getSetting($name, $default);
    }

}

if (! function_exists('trace')) {
    function trace(string|array $message): void
    {
        Log::channel('log')->info($message);
    }
}

if (! function_exists('getTreeData')) {
    function getTreeData(&$list, int $parentId = 0): array
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['pid'] == $parentId) {
                $children = getTreeData($list, $item['id']);
                ! empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }

        return $data;
    }
}

if (! function_exists('addHeaders')) {
    function addHeaders(Response $response): Response
    {
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', 1800);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Token, X-User-Token, X-Refresh-Token, X-User-Refresh-Token, Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With');

        return $response;
    }
}
