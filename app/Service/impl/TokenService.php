<?php

namespace App\Service\impl;

use App\Exceptions\HttpResponseException;
use App\Service\ITokenService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Psr\SimpleCache\InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class TokenService implements ITokenService
{
    /**
     * 数据表句柄
     */
    protected Builder $handler;

    public function __construct()
    {
        $this->handler = Db::table(config('token.table'));
    }

    /** {@inheritDoc} */
    public function set($user_id, $type): ?string
    {
        $token = Uuid::uuid4()->toString();
        $expire = $type->expire();
        $expire_time = $expire !== 0 ? time() + $expire : 0;
        $et_token = $this->getEncryptedToken($token);
        $this->handler->insert([
            'token' => $et_token,
            'type' => $type->value,
            'user_id' => $user_id,
            'create_time' => time(),
            'expire_time' => $expire_time,
        ]);
        // 每隔48小时清理一次过期缓存
        $time = time();
        $lastCacheCleanupTime = Cache::get('last_cache_cleanup_time');
        if (! $lastCacheCleanupTime || $lastCacheCleanupTime < $time - 172800) {
            try {
                Cache::set('last_cache_cleanup_time', $time);
            } catch (InvalidArgumentException $e) {
                return null;
            }
            $this->handler->where('expire_time', '<', time())->where('expire_time', '>', 0)->delete();
        }

        return $token;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $token, bool $expirationException = true): array
    {
        $data = $this->handler->where('token', $this->getEncryptedToken($token))->first();
        if (! $data) {
            throw new HttpResponseException(['msg' => __('user.invalid_token'), 'success' => false], 401);
        }
        // token过期-触发前端刷新token
        if ($data->expire_time && $data->expire_time <= time() && $expirationException) {
            if ($data->type == 'user-refresh' || $data->type == 'admin-refresh') {
                // 刷新 Token 过期重新登录
                throw new HttpResponseException(['msg' => __('user.refresh_token_expired'), 'success' => false], 401);
            }
            throw new HttpResponseException(['msg' => 'Refresh Token', 'success' => false], 202);
        }
        // 返回data
        $tokenData = [];
        $tokenData['token'] = $token;
        $tokenData['expires_in'] = $this->getExpiredIn($data->expire_time ?? 0);
        $tokenData['user_id'] = $data->user_id;
        $tokenData['type'] = $data->type;

        return $tokenData;
    }

    /**
     * {@inheritDoc}
     */
    public function check(string $token, string $type, int $user_id, bool $expirationException = true): bool
    {
        $data = $this->get($token, $expirationException);
        if (! $data || (! $expirationException && $data['expire_time'] && $data['expire_time'] <= time())) {
            return false;
        }

        return $data['type'] == $type && $data['user_id'] == $user_id;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $token): bool
    {
        $this->handler->where('token', $this->getEncryptedToken($token))->delete();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function clear(string $type, int $user_id): bool
    {
        $this->handler->where('type', $type)->where('user_id', $user_id)->delete();

        return true;
    }

    protected function getEncryptedToken(string $token): string
    {
        return hash_hmac(config('token.algo'), $token, config('token.algoKey'));
    }

    protected function getExpiredIn(int $exp_time): int
    {
        return $exp_time ? max(0, $exp_time - time()) : 365 * 86400;
    }
}
