<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CaptchaCode
 *
 * @property int $id
 * @property string $type
 * @property int $code
 * @property int $status
 * @property int $interval
 * @property string $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin IdeHelperModel
 */
class CaptchaCodeModel extends Model
{
    protected $table = 'captcha_code';

    protected $casts = [
        'code' => 'int',
        'status' => 'int',
        'interval' => 'int',
    ];

    protected $fillable = [
        'type',
        'code',
        'status',
        'interval',
        'data',
    ];
}
