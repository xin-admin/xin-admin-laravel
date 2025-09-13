<?php

namespace App\Models\Sanctum;

use App\Models\Sys\SysUserModel;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
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