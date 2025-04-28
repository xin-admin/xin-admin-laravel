<?php

namespace App\Generator;

use Illuminate\Support\Facades\DB;
use PDO;

/**
 * 数据库列实体类
 * Database column entity class
 */
class Column
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
     * @var string
     */
    private string $type;

    /**
     * 是否允许NULL / Whether NULL values are allowed
     *
     * @var bool
     */
    private bool $nullable = false;

    /**
     * 默认值 / Default value
     *
     * @var mixed
     */
    private $default = null;

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
     * 是否固定长度 (适用于字符串类型) / Whether fixed length (for string types)
     *
     * @var bool
     */
    private bool $fixed = false;

    /**
     * 是否主键
     *
     * @var bool
     */
    private bool $primaryKey = false;

    /**
     * 构造函数 / Constructor
     *
     * @param string $name 列名 / Column name
     * @param string $type 数据类型 / Data type
     */
    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    // Getter 和 Setter 方法 / Getter and Setter methods

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
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

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default): self
    {
        $this->default = $default;
        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): self
    {
        $this->length = $length;
        return $this;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    public function setPrecision(?int $precision): self
    {
        $this->precision = $precision;
        return $this;
    }

    public function getScale(): ?int
    {
        return $this->scale;
    }

    public function setScale(?int $scale): self
    {
        $this->scale = $scale;
        return $this;
    }

    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    public function setUnsigned(bool $unsigned): self
    {
        $this->unsigned = $unsigned;
        return $this;
    }

    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function setAutoIncrement(bool $autoIncrement): self
    {
        $this->autoIncrement = $autoIncrement;
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

    public function isFixed(): bool
    {
        return $this->fixed;
    }

    public function setFixed(bool $fixed): self
    {
        $this->fixed = $fixed;
        return $this;
    }

    public function setPrimaryKey(bool $primaryKey): self
    {
        $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * 生成SQL定义语句 / Generate SQL definition statement
     *
     * @return string SQL定义 / SQL definition
     */
    public function toSql(): string
    {
        $sql = "`{$this->name}` {$this->type}";

        // 添加长度/精度
        if ($this->length !== null) {
            $sql .= "({$this->length})";
        } elseif ($this->precision !== null) {
            $sql .= "({$this->precision}";
            if ($this->scale !== null) {
                $sql .= ",{$this->scale}";
            }
            $sql .= ")";
        }

        // 添加无符号
        if ($this->unsigned) {
            $sql .= " UNSIGNED";
        }

        // 添加是否允许NULL
        $sql .= $this->nullable ? " NULL" : " NOT NULL";

        // 添加默认值
        if ($this->default !== null) {
            $default = is_string($this->default) ? "'{$this->default}'" : $this->default;
            $sql .= " DEFAULT {$default}";
        }

        // 添加主键
        if ($this->primaryKey) {
            $sql .= " PRIMARY KEY";
        }

        // 添加自增
        if ($this->autoIncrement) {
            $sql .= " AUTO_INCREMENT";
        }

        // 添加注释
        if ($this->comment !== null) {
            $comment = addslashes($this->comment);
            $sql .= " COMMENT '{$comment}'";
        }

        return $sql;
    }

    /**
     * 生成Laravel迁移代码 / Generate Laravel migration code
     *
     * @return string 迁移代码 / Migration code
     */
    public function toMigration(): string
    {
        $method = $this->type;
        $migration = "\$table->{$method}('{$this->name}')";

        // 添加长度/精度
        if ($this->length !== null) {
            $migration .= "->length({$this->length})";
        } elseif ($this->precision !== null) {
            $migration .= "->precision({$this->precision})";
            if ($this->scale !== null) {
                $migration .= "->scale({$this->scale})";
            }
        }

        // 添加Key
        if ($this->primaryKey) {
            $migration .= "->primary()";
        }

        // 添加无符号
        if ($this->unsigned) {
            $migration .= "->unsigned()";
        }

        // 添加是否允许NULL
        if ($this->nullable) {
            $migration .= "->nullable()";
        }

        // 添加默认值
        if ($this->default !== null) {
            $default = var_export($this->default, true);
            $migration .= "->default({$default})";
        }

        // 添加自增
        if ($this->autoIncrement) {
            $migration .= "->autoIncrement()";
        }

        // 添加注释
        if ($this->comment !== null) {
            $comment = addslashes($this->comment);
            $migration .= "->comment('{$comment}')";
        }

        // 添加固定长度
        if ($this->fixed) {
            $migration .= "->fixed()";
        }

        return $migration . ';';
    }

    /**
     * 从Laravel Schema获取列信息创建实例 / Create instance from Laravel Schema
     *
     * @param string $tableName 表名 / Table name
     * @param string $columnName 列名 / Column name
     * @return self
     * @throws \RuntimeException 当列不存在时抛出异常 / Throws when column doesn't exist
     */
    public static function fromSchema(string $tableName, string $columnName)
    {
        // 获取数据库连接
        $pdo = DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();

        // 查询列信息
        $stmt = $pdo->prepare("
            SELECT * FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = :db_name AND TABLE_NAME = :table_name AND COLUMN_NAME = :column_name
        ");
        $stmt->execute([
            ':db_name' => $dbName,
            ':table_name' => $tableName,
            ':column_name' => $columnName,
        ]);

        // 获取列信息
        $columnInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        // 检查列是否存在
        if (!$columnInfo) {
            throw new \RuntimeException("Column {$columnName} does not exist in table {$tableName}");
        }

        // 查询字段是否为主键
        $primaryKeyStmt = $pdo->prepare("
            SELECT COUNT(*) AS is_primary_key
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = :db_name AND TABLE_NAME = :table_name AND COLUMN_NAME = :column_name AND CONSTRAINT_NAME = 'PRIMARY'
        ");
        $primaryKeyStmt->execute([
            ':db_name' => $dbName,
            ':table_name' => $tableName,
            ':column_name' => $columnName,
        ]);
        $isPrimaryKey = (bool) $primaryKeyStmt->fetch(PDO::FETCH_ASSOC)['is_primary_key'];

        // 判断字段是否为固定长度
        $isFixedLength = false;
        if (in_array($columnInfo['DATA_TYPE'], ['char', 'binary'])) {
            $isFixedLength = true;
        } elseif ($columnInfo['DATA_TYPE'] === 'varchar') {
            // 对于 VARCHAR 类型，CHARACTER_MAXIMUM_LENGTH 和 CHARACTER_OCTET_LENGTH 相同时可能表示固定长度
            $isFixedLength = ($columnInfo['CHARACTER_MAXIMUM_LENGTH'] === $columnInfo['CHARACTER_OCTET_LENGTH']);
        }


        // 创建实例并设置属性
        $column = new self($columnInfo['COLUMN_NAME'], $columnInfo['DATA_TYPE']);

        return $column
            ->setNullable($columnInfo['IS_NULLABLE'] === 'YES')
            ->setDefault($columnInfo['COLUMN_DEFAULT'])
            ->setLength($columnInfo['CHARACTER_MAXIMUM_LENGTH'])
            ->setPrecision($columnInfo['NUMERIC_PRECISION'])
            ->setScale($columnInfo['NUMERIC_SCALE'])
            ->setAutoIncrement(stripos($columnInfo['EXTRA'], 'auto_increment') !== false)
            ->setComment($columnInfo['COLUMN_COMMENT'])
            ->setPrimaryKey($isPrimaryKey)
            ->setFixed($isFixedLength);

    }
}