<?php

use App\Models\Setting\SettingGroupModel;
use Illuminate\Support\Facades\Log;

if (! function_exists('get_setting')) {
    /**
     * 获取站点的系统配置，不传递参数则获取所有配置项，配置项不存在返回 null
     *
     * @param  string  $name  变量名
     */
    function get_setting(string $name): array|string|null
    {
        $setting_name = explode('.', $name);
        $setting_group = SettingGroupModel::query()->where('key', $setting_name[0])->first();

        if (! $setting_group) {
            return null;
        }
        if (count($setting_name) > 1) {
            $setting = $setting_group->setting()->where('key', $setting_name[1])->first();
            if (! $setting) {
                return null;
            }

            return $setting->values;
        } else {
            try {
                $arr = [];
                foreach ($setting_group->setting as $set) {
                    $arr[$set['key']] = $set['values'];
                }

                return $arr;
            } catch (Exception $e) {
                return null;
            }
        }
    }

}

if (! function_exists('trace')) {
    function trace(string|array $message): void
    {
        Log::channel('log')->info($message);
    }
}
