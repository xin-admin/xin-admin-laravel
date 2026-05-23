<?php

namespace Modules\SystemTool\Base;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\SystemTool\Attributes\Setting;
use Modules\SystemTool\Enum\ESettingType;
use ReflectionClass;

/**
 * 设置定义基类
 */
abstract class SettingsDefinition
{
    /**
     * 解析后的定义缓存（类名 → 定义数组）
     *
     * @var array<string, array<string, array{config: string, type: string, description: string|null}>>
     */
    protected static array $definitions = [];

    /**
     * 从 #[Setting] Attribute 解析设置定义
     *
     * @return array<string, array{config: string, type: string, description: string|null}>
     */
    public static function getDefinition(): array
    {
        $class = static::class;

        if (! isset(self::$definitions[$class])) {
            $reflection = new ReflectionClass($class);
            $attributes = $reflection->getAttributes(Setting::class);
            $definition = [];

            foreach ($attributes as $attribute) {
                /** @var Setting $instance */
                $instance = $attribute->newInstance();
                $definition[$instance->config] = [
                    'config'      => $instance->config,
                    'type'        => $instance->type->value,
                    'description' => $instance->description,
                ];
            }

            self::$definitions[$class] = $definition;
        }

        return self::$definitions[$class];
    }

    /**
     * 判断某个 key 是否在本类的定义中
     */
    public static function hasDefinitionKey(string $key): bool
    {
        return array_key_exists($key, static::getDefinition());
    }

    /**
     * 获取数据库表名
     */
    public static function getTableName(): string
    {
        return config('app_settings.table', 'sys_app_settings');
    }

    /**
     * 将本类的所有配置项定义同步到 应用配置 表
     *
     * - 新 key 插入，值来自当前 config() 的默认值
     * - 已有 key 只更新 description，不覆盖值
     * - 部署时在 migration 中调用
     */
    public static function init(): void
    {
        $definition = static::getDefinition();
        $table = static::getTableName();

        foreach ($definition as $key => $setting) {
            $exists = DB::table($table)->where('key', $key)->exists();

            if (! $exists) {
                $value = static::getConfigValue($key);
                static::set($key, $value);
            } else {
                DB::table($table)
                    ->where('key', $key)
                    ->update([
                        'description' => $setting['description'] ?? null,
                        'updated_at'  => now(),
                    ]);
            }
        }
    }

    /**
     * 读取配置值
     *
     * @throws InvalidArgumentException 当 key 未定义时
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (! static::hasDefinitionKey($key)) {
            throw new InvalidArgumentException("Setting key '{$key}' is not defined in " . static::class);
        }

        return static::getCacheValue($key, function () use ($key, $default) {
            return static::getDBValue($key, function () use ($key, $default) {
                return static::setCacheValue(
                    key: $key,
                    value: static::getConfigValue($key, $default),
                );
            });
        });
    }

    /**
     * 写入配置值 → DB + Cache
     *
     * @throws InvalidArgumentException 当 key 未定义或类型不匹配时
     */
    public static function set(string $key, mixed $value): void
    {
        if (! static::hasDefinitionKey($key)) {
            throw new InvalidArgumentException("Setting key '{$key}' is not defined in " . static::class);
        }

        static::setDBValue($key, $value);
        static::setCacheValue($key, $value);
    }

    /**
     * 从 Laravel config 读取（最终 fallback）
     */
    public static function getConfigValue(string $key, mixed $default = null): mixed
    {
        return config($key, $default);
    }

    /**
     * 从缓存读取
     *
     * @param mixed $default 默认值或闭包 (fn() => mixed)
     */
    public static function getCacheValue(string $key, mixed $default = null): mixed
    {
        if ($default instanceof \Closure) {
            return Cache::rememberForever('app-setting-' . $key, $default);
        }

        return Cache::get('app-setting-' . $key, $default);
    }

    /**
     * 写入缓存
     */
    public static function setCacheValue(string $key, mixed $value, ?int $ttl = null): mixed
    {
        if ($ttl !== null) {
            Cache::put('app-setting-' . $key, $value, $ttl);
        } else {
            Cache::forever('app-setting-' . $key, $value);
        }

        return $value;
    }

    /**
     * 将本类所有配置项从 DB 加载到 Laravel config() 运行时
     *
     * 调用后 config('filesystems.default') 等可直接返回 DB 值。
     * 在 getConfig() 批量读取场景下避免逐个 get() 的开销。
     */
    public static function reloadIntoConfig(): void
    {
        $table = static::getTableName();
        $rows = DB::table($table)->whereIn('key', array_keys(static::getDefinition()))->get();

        foreach ($rows as $row) {
            $value = match ((int) $row->type) {
                ESettingType::String->value   => $row->s,
                ESettingType::Bool->value     => is_null($row->n) ? null : (bool) $row->n,
                ESettingType::Number->value   => is_null($row->n) ? null : (int) $row->n,
                ESettingType::Array->value    => is_null($row->e) ? null : json_decode($row->e, true),
                ESettingType::Object->value   => is_null($row->e) ? null : unserialize(base64_decode($row->e)),
                ESettingType::EncryptedString->value => is_null($row->e) ? null : \Illuminate\Support\Facades\Crypt::decrypt(base64_decode($row->e)),
                default => $row->s ?? null,
            };

            config([$row->key => $value]);
        }
    }

    /**
     * 清除某个 key 的缓存
     */
    public static function forgetCache(string $key): void
    {
        Cache::forget('app-setting-' . $key);
    }


    /**
     * 从数据库读取（按类型自动转换）
     *
     * @param mixed $default 默认值或闭包
     */
    public static function getDBValue(string $key, mixed $default = null): mixed
    {
        $rec = DB::table(static::getTableName())->where('key', $key)->first();

        if (! $rec) {
            return $default instanceof \Closure ? $default() : value($default);
        }

        return match ((int) $rec->type) {
            ESettingType::String->value   => $rec->s,
            ESettingType::Bool->value     => is_null($rec->n) ? null : (bool) $rec->n,
            ESettingType::Number->value   => is_null($rec->n) ? null : (int) $rec->n,
            ESettingType::Array->value    => is_null($rec->e) ? null : json_decode($rec->e, true),
            ESettingType::Object->value   => is_null($rec->e) ? null : unserialize(base64_decode($rec->e)),
            ESettingType::EncryptedString->value => is_null($rec->e) ? null : Crypt::decrypt(base64_decode($rec->e)),
            default => $rec->s ?? $rec->value ?? null,
        };
    }

    /**
     * 写入数据库（按类型选择列）
     *
     * @throws InvalidArgumentException
     */
    public static function setDBValue(string $key, mixed $value): void
    {
        $definition = static::getDefinition();

        if (! isset($definition[$key])) {
            throw new InvalidArgumentException("Setting key '{$key}' is not defined in " . static::class);
        }

        $type        = $definition[$key]['type'];
        $description = $definition[$key]['description'] ?? null;
        $table       = static::getTableName();

        $data = [
            'key'         => $key,
            'type'        => $type,
            'description' => $description,
            'updated_at'  => now(),
        ];

        switch ((int) $type) {
            case ESettingType::String->value:
                if (! is_string($value) && ! is_null($value)) {
                    throw new InvalidArgumentException("Value for '{$key}' must be a string.");
                }
                $data['s'] = $value;
                $data['n'] = null;
                $data['e'] = null;
                break;

            case ESettingType::Bool->value:
                if (! is_bool($value) && ! is_null($value)) {
                    throw new InvalidArgumentException("Value for '{$key}' must be a boolean.");
                }
                $data['n'] = is_null($value) ? null : (int) $value;
                $data['s'] = null;
                $data['e'] = null;
                break;

            case ESettingType::Number->value:
                if (! is_numeric($value) && ! is_null($value)) {
                    throw new InvalidArgumentException("Value for '{$key}' must be a number.");
                }
                $data['n'] = $value;
                $data['s'] = null;
                $data['e'] = null;
                break;

            case ESettingType::Array->value:
                if (! is_array($value) && ! is_null($value)) {
                    throw new InvalidArgumentException("Value for '{$key}' must be an array.");
                }
                $data['e'] = is_null($value) ? null : json_encode($value, JSON_UNESCAPED_UNICODE);
                $data['s'] = null;
                $data['n'] = null;
                break;

            case ESettingType::Object->value:
                if (! is_object($value) && ! is_null($value)) {
                    throw new InvalidArgumentException("Value for '{$key}' must be an object.");
                }
                $data['e'] = is_null($value) ? null : base64_encode(serialize($value));
                $data['s'] = null;
                $data['n'] = null;
                break;

            case ESettingType::EncryptedString->value:
                if (! is_string($value) && ! is_null($value)) {
                    throw new InvalidArgumentException("Value for '{$key}' must be a string.");
                }
                $data['e'] = is_null($value) ? null : base64_encode(Crypt::encrypt($value));
                $data['s'] = null;
                $data['n'] = null;
                break;

            default:
                throw new InvalidArgumentException("Unknown setting type '{$type}' for key '{$key}'.");
        }

        $exists = DB::table($table)->where('key', $key)->exists();

        if ($exists) {
            DB::table($table)->where('key', $key)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table($table)->insert($data);
        }
    }

    /**
     * 从数据库删除某个配置项
     */
    public static function deleteDBValue(string $key): void
    {
        DB::table(static::getTableName())->where('key', $key)->delete();
    }
}
