<?php

namespace App\Http\Controllers\Admin\Online;

use App\Attribute\Auth;
use App\Http\Controllers\Admin\Controller;
use App\Http\Admin\Requests\OnlineTableTypeRequest;
use App\Models\OnlineTableModel;
use Illuminate\Http\JsonResponse;
use App\Service\OnlineTableService;

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
    public function crud(OnlineTableTypeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $crud = new OnlineTableService();
        $sql = $crud->online($data);
        return $this->error($sql);
    }
}
