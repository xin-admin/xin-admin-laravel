<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{

    /**
     * 是否为更新请求
     * @return bool
     */
    protected function isUpdate(): bool
    {
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return true;
        }

        return false;
    }

}
