<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CaptchaCode
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
