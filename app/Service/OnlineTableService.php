<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class OnlineTableService {

    private array $columns = [];

    private array $crudConfig = [];

    private array $tableConfig = [];


    public function online($data): string
    {
        $this->tableConfig = $data['table_config'];
        $this->crudConfig = $data['crud_config'];
        $this->columns = $data['columns'];
        return $this->buildSql();
    }

    private function buildSql(): string | false
    {

        // 验证表名是否合法，防止SQL注入
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->crudConfig['sqlTableName'])) {
            return false;
        }


        $tableName = $this->crudConfig['sqlTableName'];
        // 开始构建SQL语句
        $sql = "CREATE TABLE `{$tableName}` (\n";
        $columnDefinitions = [];
        $primaryKeys = [];
        $indexes = [];

        foreach ($this->columns as $index => $column) {
            $columnName = $column['dataIndex'];
            $columnType = $column['sqlType'];
            $length = isset($column['sqlLength']) ? "({$column['sqlLength']})" : '';
            $nullOption = isset($column['null']) && $column['null'] ? 'NOT NULL' : 'NULL';
            $autoIncrement = isset($column['autoIncrement']) && $column['autoIncrement'] ? 'AUTO_INCREMENT' : '';
            $unsign = isset($column['unsign']) && $column['unsign'] ? 'UNSIGNED' : '';
            // 处理前端传递的默认值，将字符串 'null' 转换成 SQL NULL
            if (array_key_exists('defaultValue', $column)) {
                $defaultValue = $column['defaultValue'];
                if (strtolower($defaultValue) === 'null') {
                    $defaultValue = 'DEFAULT NULL';
                } elseif (is_numeric($defaultValue)) {
                    // 直接赋值数值
                    $defaultValue = "DEFAULT " . $defaultValue;
                } else {
                    // 适当地处理字符串和其他默认值
                    $defaultValue = "DEFAULT '{$defaultValue}'";
                }
            } else {
                $defaultValue = '';
            }

            $columnDefinitions[] = "`{$columnName}` {$columnType}{$length} {$unsign} {$nullOption} {$autoIncrement} {$defaultValue}";

            // 自动将第一个字段设为主键
            if ($index === 0) {
                $primaryKeys[] = "`{$columnName}`";
            }

            // 索引处理，假设可以通过column设置确定是否要索引
            if (isset($column['index']) && $column['index']) {
                $indexes[] = "INDEX `idx_{$columnName}` (`{$columnName}`)";
            }
        }

        // 合并字段定义，添加主键和索引
        $sql .= implode(",\n", $columnDefinitions);
        if (!empty($primaryKeys)) {
            $sql .= ",\nPRIMARY KEY (" . implode(', ', $primaryKeys) . ")";
        }
        if (!empty($indexes)) {
            $sql .= ",\n" . implode(",\n", $indexes);
        }
        $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        // 运行SQL前的安全检查，使用参数化或框架封装确保SQL执行安全
//        try {
//            // 这里使用 PDO 或者 Laravel 的 DB 包进行 SQL 执行
//            DB::statement($sql);
//        } catch (\Exception $e) {
//            abort(500, "Error creating table: " . $e->getMessage());
//        }

        // 最终的SQL字符串
        return $sql;
    }
}
