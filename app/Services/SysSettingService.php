<?php

namespace App\Services;

use App\Models\Sys\SysSettingGroupModel;
use App\Models\Sys\SysSettingItemsModel;
use App\Support\Enum\SettingType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * 系统设置服务
 */
class SysSettingService
{
    /**
     * 缓存过期时间（秒），默认30天
     */
    private const CACHE_TTL = 60 * 60 * 24 * 30;

    /**
     * 刷新系统设置缓存
     * @return bool
     */
    public static function refreshSettings(): bool
    {
        try {
            $groups = SysSettingGroupModel::with('settings')->get();
            $settings = [];
            
            foreach ($groups as $group) {
                $settings[$group->key] = [];
                foreach ($group->settings as $setting) {
                    // 根据类型自动转换值
                    $settings[$group->key][$setting->key] = self::castValue($setting->values, $setting->type);
                }
            }
            
            // 使用带过期时间的缓存，而非永久缓存
            Cache::put(self::getCacheKey(), $settings, self::CACHE_TTL);
            
            Log::info('系统配置缓存已刷新', ['settings_count' => count($settings)]);
            return true;
        } catch (\Throwable $e) {
            Log::error('刷新系统配置缓存失败', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * 获取设置
     * @param string|null $name 设置名称，格式：'group.key' 或 'group'，为null时返回所有配置
     * @param mixed $default 默认值
     * @return mixed
     */
    public static function getSetting(?string $name = null, mixed $default = null): mixed
    {
        $settings = Cache::get(self::getCacheKey());
        
        // 缓存不存在时重新加载
        if (empty($settings)) {
            self::refreshSettings();
            $settings = Cache::get(self::getCacheKey(), []);
        }
        
        // 返回所有配置
        if (is_null($name)) {
            return $settings;
        }
        
        // 解析配置路径
        $keys = explode('.', $name);
        
        // 支持多级获取：group.key 或 group
        if (count($keys) === 2) {
            return $settings[$keys[0]][$keys[1]] ?? $default;
        } elseif (count($keys) === 1) {
            return $settings[$keys[0]] ?? $default;
        }
        
        return $default;
    }

    /**
     * 设置配置项（更新配置并刷新缓存）
     * @param int $id 设置ID
     * @param mixed $value 设置值
     * @return bool
     */
    public static function setSetting(int $id, mixed $value): bool
    {
        try {
            
            // 查找设置组和设置项
            $setting = SysSettingItemsModel::find($id);
            if (!$setting) {
                throw new \RuntimeException("设置项不存在: {$id}");
            }
            
            // 更新值
            $setting->values = is_array($value) || is_object($value) ? json_encode($value) : (string)$value;
            $setting->save();
            
            return true;
        } catch (\Throwable $e) {
            Log::error('设置配置项失败', [
                'id' => $id,
                'value' => $value,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * 检查配置项是否存在
     * @param string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        $keys = explode('.', $name);
        $settings = Cache::get(self::getCacheKey(), []);
        
        if (count($keys) === 2) {
            return isset($settings[$keys[0]][$keys[1]]);
        } elseif (count($keys) === 1) {
            return isset($settings[$keys[0]]);
        }
        
        return false;
    }

    /**
     * 清除设置缓存
     * @return bool
     */
    public static function clearCache(): bool
    {
        return Cache::forget(self::getCacheKey());
    }

    /**
     * 根据类型转换值
     * @param mixed $value 原始值
     * @param string $type 类型（对应前端表单组件类型）
     * @return mixed
     */
    private static function castValue(mixed $value, string $type): mixed
    {
        // 空值直接返回
        if (is_null($value) || $value === '') {
            return $value;
        }
        
        // 尝试转换为枚举类型
        $settingType = SettingType::fromString($type);
        
        // 如果无法识别类型，返回字符串
        if (is_null($settingType)) {
            return (string)$value;
        }
        
        // 使用枚举判断进行类型转换
        if ($settingType->isNumeric()) {
            return is_numeric($value) 
                ? (str_contains((string)$value, '.') ? (float)$value : (int)$value) 
                : $value;
        }
        
        if ($settingType->isBoolean()) {
            return self::toBool($value);
        }
        
        if ($settingType->isArray()) {
            return self::toArrayValue($value);
        }
        
        // 根据具体类型处理
        return match($settingType) {
            SettingType::INPUT => (string)$value,
            SettingType::TEXTAREA => (string)$value,
            SettingType::RADIO => is_numeric($value) ? (int)$value : (string)$value,
            SettingType::SELECT => is_numeric($value) ? (int)$value : (string)$value,
            SettingType::DATE_PICKER => (string)$value,
            default => (string)$value,
        };
    }

    /**
     * 转换为布尔值
     * @param mixed $value
     * @return bool
     */
    private static function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        
        if (is_numeric($value)) {
            return (bool)(int)$value;
        }
        
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['true', '1', 'yes', 'on'], true);
        }
        
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * 转换为数组值（兼容 Checkbox、JSON、Array 类型）
     * @param mixed $value
     * @return array|bool
     */
    private static function toArrayValue(mixed $value): array|bool
    {
        // 如果是 JSON 字符串，尝试解析为数组
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            // Checkbox 单个值转为布尔
            return self::toBool($value);
        }
        
        // 已经是数组直接返回
        if (is_array($value)) {
            return $value;
        }
        
        // 其他情况转为布尔（Checkbox 单选）
        return self::toBool($value);
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