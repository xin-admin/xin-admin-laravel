<?php

namespace App\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRechargeRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:user,id',
            'mode' => 'required|string|in:inc,dec,end',
            'amount' => 'required|integer',
            'remark' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
