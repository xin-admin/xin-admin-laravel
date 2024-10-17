<?php


namespace App\Models;

use App\Enum\ShowType as ShowTypeEnum;
use App\Exception\HttpResponseException;
use Illuminate\Database\Eloquent\Model;

/**
 * 基础模型
 */
class BaseModel extends Model
{

    /**
     * 错误信息
     */
    private string $errorMsg = '';

    /**
     * 获取模型错误信息
     */
    public function getErrorMsg(): string
    {
        return $this->errorMsg;
    }

    /**
     * 设置模型错误信息
     *
     * @return mixed|string
     */
    public function setErrorMsg($str = ''): mixed
    {
        return $this->errorMsg = $str;
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        $fun = function () {
            if (env('WEB_NAME') && env('WEB_NAME') == 'xin_test') {
                $showType = ShowTypeEnum::WARN_NOTIFICATION->value;
                throw new HttpResponseException([
                    'success' => false,
                    'msg' => '演示站已禁止此操作',
                    'description' => '当前站点禁止此操作，请获取本地项目在本地测试！',
                    'showType' => $showType,
                ]);
            }
        };
        static::creating($fun);
        static::saving($fun);
        static::deleting($fun);
    }
}
