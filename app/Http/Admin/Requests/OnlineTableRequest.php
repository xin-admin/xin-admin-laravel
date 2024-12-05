<?php

namespace App\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnlineTableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|integer|exists:online_table,id',
            'table_name' => 'required',
            'columns' => 'required|json',
            'crud_config' => 'required|json',
            'table_config' => 'required|json',
            'describe' => 'required',
        ];
    }
}
