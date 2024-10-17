<?php


namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\File\FileModel;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Xin\Token;

/**
 * 用户模型
 */
class UserModel extends BaseModel
{
    protected $table = 'user';

    protected $hidden = [
        'password', 'created_at', 'updated_at', 'avatar',
    ];

    protected $appends = ['avatar_url'];


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
                return $this->avatar->preview_url ?? '';
            },
        );
    }

    protected function money(): Attribute
    {
        return new Attribute(
            get: function ($data) {
                return number_format($data / 100, 2, '.', '');
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
            $user = self::query()->where('username', $username)->first();
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
        } catch (Exception $e) {
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
                $token->set($data['token'], 'user', $user_id, 600) &&
                $token->set($data['refresh_token'], 'user-refresh', $user_id, 2592000)
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
            $token->clear('user', $user['id']);
            $token->clear('user-refresh', $user['id']);

            return true;
        } catch (\Exception $e) {
            $this->setErrorMsg($e->getMessage());

            return false;
        }
    }
}
