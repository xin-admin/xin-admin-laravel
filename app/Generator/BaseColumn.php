<?php

namespace App\Generator;

use App\Generator\Enum\SqlColumnType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 数据库列实体类
 * Database column entity class
 */
class BaseColumn
{
    /**
     * 列名 / Column name
     *
     * @var string
     */
    private string $name;

    /**
     * 数据类型 / Data type (e.g. 'integer', 'string', 'datetime')
     *
     * @var SqlColumnType
     */
    private SqlColumnType $type;

    /**
     * 是否允许NULL / Whether NULL values are allowed
     *
     * @var bool
     */
    private bool $nullable = false;

    /**
     * 默认值 / Default value
     *
     * @var string | null
     */
    private ?string $default = null;

    /**
     * 长度限制 (适用于字符串类型) / Length limit (for string types)
     *
     * @var int|null
     */
    private ?int $length = null;

    /**
     * 精度 (适用于小数类型) / Precision (for decimal types)
     *
     * @var int|null
     */
    private ?int $precision = null;

    /**
     * 小数位数 (适用于小数类型) / Scale (for decimal types)
     *
     * @var int|null
     */
    private ?int $scale = null;

    /**
     * 是否无符号 (适用于数值类型) / Whether unsigned (for numeric types)
     *
     * @var bool
     */
    private bool $unsigned = false;

    /**
     * 是否自增 / Whether auto-incrementing
     *
     * @var bool
     */
    private bool $autoIncrement = false;

    /**
     * 列注释 / Column comment
     *
     * @var string|null
     */
    private ?string $comment = null;

    /**
     * 是否主键 / The field column is the primary key
     *
     * @var bool
     */
    private bool $primaryKey = false;

    /**
     * SET、ENUM 类型的枚举 / the preset values if the column is "enum" or "set"
     *
     * @var string[]
     */
    protected array $presetValues = [];

    /**
     * 构造函数 / Constructor
     *
     * @param string $name 列名 / Column name
     * @param string $type 数据类型 / Data type
     */
    public function __construct(string $name, string $type)
    {
        $this->name = $name;

        $this->type = SqlColumnType::from($type);
    }

    // Getter 和 Setter 方法 / Getter and Setter methods

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): SqlColumnType
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function setNullable(bool $nullable): self
    {
        $this->nullable = $nullable;
        return $this;
    }

    public function getDefault(): ?string
    {
        return $this->default;
    }

    public function setDefault(?string $default): self
    {
        if ($default !== null) {
            $default = stripslashes($default);
            if (strtoupper($default) === 'NULL') {
                $default = null;
            }
        }

        $this->default = $default;
        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): self
    {
        $this->length = match ($this->type) {
            SqlColumnType::CHAR,
            SqlColumnType::VARCHAR,
            SqlColumnType::BINARY,
            SqlColumnType::VARBINARY,
            SqlColumnType::TINY_INT,
            SqlColumnType::SMALL_INT,
            SqlColumnType::MEDIUM_INT,
            SqlColumnType::INT,
            SqlColumnType::INTEGER,
            SqlColumnType::BIGINT => $length,
            default => null,
        };

        return $this;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    public function setPrecision(?int $precision): self
    {
        $this->precision = match ($this->type) {
            SqlColumnType::DECIMAL,
            SqlColumnType::NUMERIC => $precision,
            default => null,
        };

        return $this;
    }

    public function getScale(): ?int
    {
        return $this->scale;
    }

    public function setScale(?int $scale): self
    {
        $this->scale = match ($this->type) {
            SqlColumnType::DECIMAL,
            SqlColumnType::NUMERIC => $scale,
            default => null,
        };

        return $this;
    }

    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    public function setUnsigned(bool $unsigned): self
    {
        $this->unsigned = match ($this->type) {
            SqlColumnType::TINY_INT,
            SqlColumnType::SMALL_INT,
            SqlColumnType::MEDIUM_INT,
            SqlColumnType::INT,
            SqlColumnType::INTEGER,
            SqlColumnType::BIGINT,
            SqlColumnType::FLOAT,
            SqlColumnType::DOUBLE => $unsigned,
            default => false,
        };
        return $this;
    }

    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function setAutoIncrement(bool $autoIncrement): self
    {
        $this->autoIncrement = match ($this->type) {
            SqlColumnType::TINY_INT,
            SqlColumnType::SMALL_INT,
            SqlColumnType::MEDIUM_INT,
            SqlColumnType::INT,
            SqlColumnType::INTEGER,
            SqlColumnType::BIGINT => $autoIncrement,
            default => false,
        };
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getPrimaryKey(): ?bool
    {
        return $this->primaryKey;
    }

    public function setPrimaryKey(bool $primaryKey): self
    {
        $this->primaryKey = $primaryKey;
        return $this;
    }

    public  function getPresetValues(): array
    {
        return $this->presetValues;
    }

    public function setPresetValues(string | array | null $presetValues): self
    {
        if(! in_array($this->type, [ SqlColumnType::ENUM, SqlColumnType::SET ])) {
            return $this;
        }
        if ($presetValues == null) {
            return $this;
        }
        if (is_array($presetValues)) {
            $this->presetValues = $presetValues;
            return $this;
        }
        if (str_contains($presetValues, 'enum')) {
            $value = substr(
                $presetValues,
                strlen("enum('"),
                -strlen("')"),
            );
            $this->presetValues =  explode("','", $value);
        } elseif(str_contains($presetValues, 'set')){
            $value = substr(
                $presetValues,
                strlen("set('"),
                -strlen("')"),
            );
            $this->presetValues = explode("','", $value);
        }else {
            $this->presetValues = explode(',', $presetValues);
        }

        return $this;
    }

    /**
     * 从 Schema获取列信息创建实例 / Create instance from Schema
     *
     * @param string $tableName 表名 / Table name
     * @param string $columnName 列名 / Column name
     * @return self
     * @throws \RuntimeException 当列不存在时抛出异常 / Throws when column doesn't exist
     */
    public static function fromSchema(string $tableName, string $columnName): self
    {
        // 检查表是否存在
        if (!Schema::hasTable($tableName)) {
            throw new \RuntimeException("Table {$tableName} does not exist.");
        }

        // 检查列是否存在
        if (!Schema::hasColumn($tableName, $columnName)) {
            throw new \RuntimeException("Column {$columnName} does not exist in table {$tableName}");
        }

        // 获取列信息（需要执行原生查询）
        $columnInfo = (array) DB::selectOne(/** @lang text */ "
            SELECT * FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
        ", [
            DB::getDatabaseName(),
            $tableName,
            $columnName
        ]);

        // 创建实例并设置属性
        $column = new self($columnInfo['COLUMN_NAME'], $columnInfo['DATA_TYPE']);

        return $column
            ->setNullable($columnInfo['IS_NULLABLE'] == 'NO')
            ->setDefault($columnInfo['COLUMN_DEFAULT'])
            ->setLength($columnInfo['CHARACTER_MAXIMUM_LENGTH'])
            ->setPrecision($columnInfo['NUMERIC_PRECISION'])
            ->setScale($columnInfo['NUMERIC_SCALE'])
            ->setUnsigned(str_contains($columnInfo['COLUMN_TYPE'], 'unsigned'))
            ->setAutoIncrement(str_contains($columnInfo['EXTRA'], 'auto_increment'))
            ->setComment($columnInfo['COLUMN_COMMENT'])
            ->setPresetValues($columnInfo['COLUMN_TYPE'])
            ->setPrimaryKey($columnInfo['COLUMN_KEY'] === 'PRI');
    }

    /**
     * 从 Request Column 获取列信息创建实例 / Create instance Request Column
     *
     * @param array $columnData 列数据 / Column Data
     * @return self
     */
    public function formRequestColumn(array $columnData): self
    {
        // 创建实例并设置属性
        $column = new self($columnData['name'], $columnData['type']);

        isset($columnData['nullable']) && $column->setNullable($columnData['nullable']);
        isset($columnData['default']) && $column->setDefault($columnData['default']);
        isset($columnData['length']) && $column->setLength($columnData['length']);
        isset($columnData['precision']) && $column->setPrecision($columnData['precision']);
        isset($columnData['scale']) && $column->setScale($columnData['scale']);
        isset($columnData['unsigned']) && $column->setUnsigned($columnData['unsigned']);
        isset($columnData['autoIncrement']) && $column->setAutoIncrement($columnData['autoIncrement']);
        isset($columnData['comment']) && $column->setComment($columnData['comment']);
        isset($columnData['primaryKey']) && $column->setPrimaryKey($columnData['primaryKey']);
        isset($columnData['presetValues']) && $column->setPresetValues($columnData['presetValues']);

        return $column;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'primaryKey' => $this->primaryKey,
            'presetValues' => $this->presetValues,
            'nullable' => $this->nullable,
            'default' => $this->default,
            'length' => $this->length,
            'precision' => $this->precision,
            'scale' => $this->scale,
            'unsigned' => $this->unsigned,
            'autoincrement' => $this->autoIncrement,
            'comment' => $this->comment,
        ];
    }
}