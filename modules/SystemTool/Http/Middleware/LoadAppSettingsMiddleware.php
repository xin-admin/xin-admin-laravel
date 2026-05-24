<?php

namespace Modules\SystemTool\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Modules\SystemTool\Base\SettingsDefinition;
use Modules\SystemTool\Enum\ESettingType;
use Symfony\Component\HttpFoundation\Response;

/**
 * 全局中间件 — 从缓存加载 DB 应用设置到 config() 运行时
 *
 * 每次请求自动执行，确保 config('mail.*') / config('filesystems.*') 等
 * 返回的是数据库中的值而非配置文件的默认值。
 *
 * 聚合缓存键名: SettingsDefinition::AGGREGATE_CACHE_KEY
 * 在 SettingsDefinition::set() 写入时会自动清除该缓存，
 * 使下一次请求重新从 DB 加载最新值。
 */
class LoadAppSettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 从聚合缓存加载（rememberForever = 永不过期，仅 set() 时清除）
        $settings = Cache::rememberForever(SettingsDefinition::AGGREGATE_CACHE_KEY, function () {
            return $this->loadAllFromDB();
        });

        // 写入 Laravel config() 运行时
        foreach ($settings as $key => $value) {
            config([$key => $value]);
        }

        return $next($request);
    }

    /**
     * 从数据库加载所有应用设置，按类型转换后返回 key → value 数组
     */
    protected function loadAllFromDB(): array
    {
        $rows = DB::table(SettingsDefinition::getTableName())->get();
        $result = [];

        foreach ($rows as $row) {
            $result[$row->key] = match ((int) $row->type) {
                ESettingType::String->value           => $row->s,
                ESettingType::Bool->value             => is_null($row->n) ? null : (bool) $row->n,
                ESettingType::Number->value           => is_null($row->n) ? null : (int) $row->n,
                ESettingType::Array->value            => is_null($row->e) ? null : json_decode($row->e, true),
                ESettingType::Object->value           => is_null($row->e) ? null : unserialize(base64_decode($row->e)),
                ESettingType::EncryptedString->value  => is_null($row->e) ? null : Crypt::decrypt(base64_decode($row->e)),
                default                               => $row->s ?? null,
            };
        }

        return $result;
    }
}
