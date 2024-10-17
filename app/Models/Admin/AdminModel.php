<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use App\Models\File\FileModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Xin\Token;

/**
 * 管理员模型
 */
class AdminModel extends BaseModel
{
    protected $table = 'admin';

    protected $hidden = ['password', 'avatar'];
    protected $appends = ['rules', 'avatar_url'];

    protected $fillable = ['username', 'nickname', 'email', 'mobile', 'avatar_id', 'sex', 'group_id', 'status'];

    /**
     * 关联用户头像表
     */
    public function avatar(): HasOne
    {
        return $this->hasOne(FileModel::class, 'file_id', 'avatar_id');
    }

    protected function avatarUrl(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->avatar->preview_url ?? null;
            },
        );
    }


    protected function rules(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                if ($value == '*') {
                    return AdminRuleModel::query()
                        ->where('status', 1)
                        ->pluck('id')
                        ->toArray();
                } else {
                    return array_map('intval', explode(',', $value));
                }
            },
        );
    }

    /**
     * 用户名密码登录
     *
     * @param  string  $username  用户名
     * @param  string  $password  密码
     */
    public function login(string $username, string $password): bool|array
    {
        try {
            $user = self::query()->where('username', '=', $username)->first();
            if (! $user) {
                $this->setErrorMsg('用户不存在');

                return false;
            }
            // 验证密码
            if (! password_verify($password, $user['password'])) {
                $this->setErrorMsg('密码错误');

                return false;
            }

            return $this->getToken($user['id']);
        } catch (\Exception $e) {
            $this->setErrorMsg($e->getMessage());

            return false;
        }
    }

    /**
     * 退出登录
     */
    public function logout($user_id): bool
    {
        try {
            $user = self::query()->find($user_id);
            if (! $user) {
                $this->setErrorMsg('用户不存在');

                return false;
            }
            $token = new Token;
            $token->clear('admin', $user['id']);
            $token->clear('admin-refresh', $user['id']);

            return true;
        } catch (\Exception $e) {
            $this->setErrorMsg($e->getMessage());

            return false;
        }
    }

    /**
     * 获取 Token
     */
    private function getToken(int $user_id): array|false
    {
        try {
            $token = new Token;
            $data = [];
            $data['refresh_token'] = md5(random_bytes(10));
            $data['token'] = md5(random_bytes(10));
            $data['id'] = $user_id;
            if (
                $token->set($data['token'], 'admin', $user_id, 600) &&
                $token->set($data['refresh_token'], 'admin-refresh', $user_id, 2592000)
            ) {
                return $data;
            } else {
                $this->setErrorMsg('token 生成失败');

                return false;
            }
        } catch (\Exception $e) {
            $this->setErrorMsg($e->getMessage());

            return false;
        }
    }
}
