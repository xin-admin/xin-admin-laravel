<?php

namespace App\Generator\Controllers;

use App\Common\Trait\RequestJson;
use App\Generator\BaseColumn;
use App\Generator\Requests\GenRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

#[RequestMapping('/system/gen', 'system.gen')]
class GenController
{
    use RequestJson;

    public array $noPermission = ['generate', 'importSql', 'getTableList'];

    #[PostMapping]
    public function generate(GenRequest $request): JsonResponse
    {
        $data = $request->validated();
        return $this->success('ok');
    }

    #[GetMapping('/importSql/{table}')]
    public function importSql(string $table): JsonResponse
    {
        $tableName =  substr($table, strlen(DB::getTablePrefix()));
        $data = new Collection(Schema::getColumns(Schema::getCurrentSchemaName() . '.' . $tableName));
        $column =  $data->map(function ($column) use ($tableName) {
            return BaseColumn::fromSchema($tableName, $column['name'])->toArray();
        });
        return $this->success($column->toArray());
    }

    #[GetMapping('/getTableList')]
    public function getTableList(): JsonResponse
    {
        // 获取所有的数据表
        $tables = collect(Schema::getTables())->map(fn ($table) => $table['name'])->toArray();
        return $this->success($tables);
    }

}