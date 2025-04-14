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