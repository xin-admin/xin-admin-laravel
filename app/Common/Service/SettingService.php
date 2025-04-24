<?php

namespace App\Common\Service;

use App\Common\Models\SettingGroupModel;
use Illuminate\Support\Facades\Cache;

/**
 * 系统设置服务
 */
class SettingService
{

    /**
     * 刷新系统设置缓存
     * @return void
     */
    public static function refreshSettings(): void
    {
        $groups = SettingGroupModel::with('settings')->get();
        $settings = [];
        foreach ($groups as $group) {
            $settings[$group->key] = [];
            foreach ($group->settings as $setting) {
                $settings[$group->key][$setting->key] = $setting->values;
            }
        }
        Cache::forever(self::getCacheKey(), $settings);
    }

    /**
     * 获取设置
     * @param string $name
     * @param $default
     * @return mixed
     */
    public static function getSetting(string $name, $default = null): mixed
    {
        $name = explode('.', $name);
        $settings = Cache::get(self::getCacheKey());
        if (count($name) > 1) {
            return $settings[$name[0]][$name[1]] ?? $default;
        } else {
            return $settings[$name[0]] ?? $default;
        }
    }

    /**
     * 获取缓存KEY
     * @return string
     */
    private static function getCacheKey(): string
    {
        return env('SETTING_CACHE_KEY', 'app_settings');
    }
}