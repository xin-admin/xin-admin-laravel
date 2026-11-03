<?php

namespace App\Services;

use App\Models\SysSiteConfigGroupModel;
use App\Models\SysSiteConfigItemsModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 系统设置服务
 */
class SysSiteConfigService
{
    /**
     * 缓存过期时间（秒），默认30天
     */
    private const int|float CACHE_TTL = 60 * 60 * 24 * 30;

    /**
     * 缓存键
     */
    private const string CACHE_KEY = 'site_config';

    /**
     * 请求级缓存(同一请求内只读一次缓存驱动)
     *
     * @var array<string, array<string, mixed>>|null
     */
    private static ?array $memoized = null;

    /**
     * 刷新系统设置缓存
     * @return bool
     */
    public static function refreshSiteConfig(): bool
    {
        try {
            $configs = SysSiteConfigGroupModel::with('configs')
                ->get()
                ->mapWithKeys(fn($group) => [
                    $group->key => $group->configs
                        ->mapWithKeys(fn($config) => [ $config->key => $config->values ])
                        ->toArray()
                ])
                ->toArray();

            Cache::put(self::CACHE_KEY, $configs, self::CACHE_TTL);
            self::$memoized = $configs;

            return true;

        } catch (Throwable $e) {
            Log::error('刷新系统配置缓存失败', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * 获取设置
     * 格式：'group.key' 或 'group'，为null时返回所有配置
     */
    public static function getSiteConfig(?string $name = null, mixed $default = null): mixed
    {
        $configs = self::$memoized ??= self::loadConfigs();

        // 返回所有配置
        if (is_null($name)) {
            return $configs;
        }

        // 解析配置路径
        $keys = explode('.', $name);

        // 支持多级获取：group.key 或 group
        if (count($keys) === 2) {
            return $configs[$keys[0]][$keys[1]] ?? $default;
        } elseif (count($keys) === 1) {
            return $configs[$keys[0]] ?? $default;
        }

        return $default;
    }

    /**
     * 从缓存驱动加载配置,缓存不存在时查库回填
     *
     * @return array<string, array<string, mixed>>
     */
    private static function loadConfigs(): array
    {
        $configs = Cache::get(self::CACHE_KEY);

        if (empty($configs)) {
            self::refreshSiteConfig();
            $configs = Cache::get(self::CACHE_KEY, []);
        }

        return $configs;
    }

    /**
     * 批量保存设置项
     * [['id' => int, 'values' => mixed], ...]
     */
    public static function batchSaveSiteConfig(array $configs): array
    {
        $errors = [];
        $ids = array_column($configs, 'id');
        $items = SysSiteConfigItemsModel::whereIn('id', $ids)->get()->keyBy('id');

        // 先验证所有 ID 是否存在，收集缺失项信息
        foreach ($configs as $item) {
            $id = (int)$item['id'];
            if (!$items->has($id)) {
                $errors[] = [
                    'key' => "unknown_{$id}",
                    'title' => "未知设置(ID: {$id})",
                ];
            }
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            DB::transaction(function () use ($configs, $items) {
                foreach ($configs as $item) {
                    $id = (int)$item['id'];
                    $model = $items->get($id);
                    $value = $item['value'];
                    $model->values = is_array($value) || is_object($value) ? json_encode($value) : (string)$value;
                    $model->save();
                }
            });
            return ['success' => true, 'errors' => []];
        } catch (Throwable $e) {
            return ['success' => false, 'errors' => $e->getMessage()];
        }
    }
}
