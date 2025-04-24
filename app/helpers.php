<?php

use App\Common\Service\SettingService;

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

if (! function_exists('web_path')) {
    /**
     * Get the path to the web of the install.
     *
     * @param string $path
     * @return string
     */
    function web_path(string $path = ''): string
    {
        return base_path('web'. DIRECTORY_SEPARATOR . $path);
    }
}
