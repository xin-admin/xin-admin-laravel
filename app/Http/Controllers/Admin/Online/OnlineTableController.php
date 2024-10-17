<?php

namespace App\Http\Controllers\Admin\Online;

use App\Attribute\Auth;
use App\Http\Controllers\Admin\Controller;
use App\Models\OnlineTableModel;
use Illuminate\Http\JsonResponse;
use App\Service\OnlineTableService;
use Illuminate\Support\Facades\Validator;

class OnlineTableController extends Controller
{
    protected string $model = OnlineTableModel::class;

    protected array $searchField = [
        'id' => '=',
        'table_name' => 'like',
        'created_at' => 'date',
        'updated_at' => 'date'
    ];

    protected string $authName = 'online.table';

    protected array $rule = [
        'table_name' => 'required',
        'columns' => 'required|json',
        'crud_config' => 'required|json',
        'table_config' => 'required|json',
        'describe' => 'required'
    ];

    /**
     * 获取 CRUD 数据
     */
    #[Auth('getData')]
    public function getData(): JsonResponse
    {
        $id = request()->param('id');
        if (!$id) {
            return $this->warn('id不存在');
        }
        $data = $this->model::query()->select('columns,table_config,crud_config')->find($id);
        if (!$data) {
            return $this->warn('表单不存在');
        }
        return $this->success(compact('data'));
    }

    /**
     * CRUD
     */
    #[Auth('crud')]
    public function crud(): JsonResponse
    {
        $data = request()->all();
        $validator = Validator::make($data, array_merge([
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ], $this->rule), $this->message);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $crud = new OnlineTableService($validator->validate());
        return $this->error('ok');
    }
}
