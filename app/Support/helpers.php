<?php

use App\Services\SysSettingService;

if (! function_exists('setting')) {
    /**
     * 获取或设置系统配置
     *
     * @param  string|null  $name  配置名称，格式：'group.key' 或 'group'，为null时返回所有配置
     * @param  mixed|null  $default  默认值（仅在获取时使用）
     * @return mixed
     *
     * @example
     *   setting('site.name')           // 获取配置
     *   setting('site.name', 'Default') // 带默认值获取
     *   setting('site')                 // 获取整个组
     *   setting()                       // 获取所有配置
     */
    function setting(?string $name = null, mixed $default = null): mixed
    {
        return SysSettingService::getSetting($name, $default);
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
