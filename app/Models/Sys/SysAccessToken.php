<?php

namespace App\Models\Sys;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class SysAccessToken extends SanctumPersonalAccessToken
{
    protected $table = 'sys_access_token';

    public function can($ability): bool
    {
        if (
            $this->tokenable_type == SysUserModel::class
            && $this->tokenable_id == 1
        ) {
            return true;
        }
        return in_array('*', $this->abilities) ||
            array_key_exists($ability, array_flip($this->abilities));
    }
}