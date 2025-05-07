<?php

namespace App\Generator;

use App\Generator\Enum\MigrationType;
use App\Generator\Enum\SqlColumnType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Column
{
    private const REMEMBER_TOKEN_LENGTH = 100;

    protected bool $autoincrement;

    protected ?string $charset = null;

    protected ?string $collation = null;

    protected ?string $comment = null;

    protected ?string $default = null;

    protected ?int $length = null;

    private string $tableName;

    protected string $name;

    protected bool $notNull;

    protected bool $onUpdateCurrentTimestamp;

    protected ?int $precision = null;

    /**
     * @var string[]
     */
    protected array $presetValues;

    protected bool $rawDefault;

    protected int $scale;

    protected MigrationType $type;

    protected bool $unsigned = false;

    protected ?string $virtualDefinition = null;

    protected ?string $storedDefinition = null;

    protected ?string $spatialSubType = null;

    protected ?int $spatialSrID = null;

    private array $column;

    public function __construct(string $tableName, array $column)
    {
        $this->column                    = $column;
        $this->tableName                 = $tableName;
        $this->name                      = $column['name'];
        $this->type                      = SqlColumnType::tryFrom($column['type_name'])->toMigration();
        $this->length                    = $this->parseLength($column['type']);
        [$this->precision, $this->scale] = $this->parsePrecisionAndScale($column['type']);
        $this->comment                   = $this->escapeComment($column['comment']);
        $this->notNull                   = !$column['nullable'];
        $this->collation                 = $column['collation'] !== null && $column['collation'] !== '' ? $column['collation'] : null;
        $this->charset                   = null;
        $this->autoincrement             = $column['auto_increment'];
        $this->presetValues              = [];
        $this->onUpdateCurrentTimestamp  = false;
        $this->rawDefault                = false;
        $this->virtualDefinition         = $column['generation'] !== null && $column['generation']['type'] === 'virtual' ? $column['generation']['expression'] : null;
        $this->storedDefinition          = $column['generation'] !== null && $column['generation']['type'] === 'stored' ? $column['generation']['expression'] : null ;
        $this->spatialSubType            = null;
        $this->spatialSrID               = null;
        $this->default                   = $this->escapeDefault($column['default']);
        $this->unsigned                  = str_contains($column['type'], 'unsigned');

        $this->setTypeToSoftDeletes();
        $this->setTypeToRememberToken();
        $this->setTypeToIncrements();
        $this->setTypeToUnsigned();
        $this->setVirtualDefinition();
        $this->setStoredDefinition();

        switch ($this->type) {
            case MigrationType::UNSIGNED_TINY_INTEGER:
            case MigrationType::TINY_INTEGER:
                if ($this->isBoolean()) {
                    $this->type = MigrationType::BOOLEAN;
                }

                break;

            case MigrationType::ENUM:
                $this->presetValues = $this->getEnumPresetValues($column['type']);
                break;

            case MigrationType::SET:
                $this->presetValues = $this->getSetPresetValues($column['type']);
                break;

            case MigrationType::SOFT_DELETES:
            case MigrationType::SOFT_DELETES_TZ:
            case MigrationType::DATETIME:
            case MigrationType::DATETIME_TZ:
            case MigrationType::TIMESTAMP:
            case MigrationType::TIMESTAMP_TZ:
                $this->onUpdateCurrentTimestamp = $this->hasOnUpdateCurrentTimestamp();
                $this->flattenCurrentTimestamp();

                break;

            case MigrationType::GEOGRAPHY:
            case MigrationType::GEOMETRY:
            case MigrationType::GEOMETRY_COLLECTION:
            case MigrationType::LINE_STRING:
            case MigrationType::MULTI_LINE_STRING:
            case MigrationType::POINT:
            case MigrationType::MULTI_POINT:
            case MigrationType::POLYGON:
            case MigrationType::MULTI_POLYGON:
                $this->setRealSpatialColumn();
                break;

            default:
        }


    }

    /**
     * Check if the column is "tinyint(1)".
     */
    private function isBoolean(): bool
    {
        if ($this->autoincrement) {
            return false;
        }

        return Str::startsWith($this->column['type'], 'tinyint(1)');
    }

    /**
     * Get the column name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the column type.
     */
    public function getType(): MigrationType
    {
        return $this->type;
    }

    /**
     * Get the column length.
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * Get the column scale.
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * Get the column precision.
     */
    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    /**
     * Get the column comment.
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Check if the column is unsigned.
     */
    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    /**
     * Check if the column is not null.
     */
    public function isNotNull(): bool
    {
        return $this->notNull;
    }

    /**
     * Get the column default value.
     */
    public function getDefault(): ?string
    {
        return $this->default;
    }

    /**
     * Get the column collation.
     */
    public function getCollation(): ?string
    {
        return $this->collation;
    }

    /**
     * Get the column charset.
     */
    public function getCharset(): ?string
    {
        return $this->charset;
    }

    /**
     * Check if the column is autoincrement.
     */
    public function isAutoincrement(): bool
    {
        return $this->autoincrement;
    }

    /**
     * Get the column precision.
     */
    public function getPresetValues(): array
    {
        return $this->presetValues;
    }

    /**
     * Get the spatial column subtype.
     */
    public function getSpatialSubType(): ?string
    {
        return $this->spatialSubType;
    }

    /**
     * Get the spatial column srID.
     */
    public function getSpatialSrID(): ?int
    {
        return $this->spatialSrID;
    }

    /**
     * Check if the column uses "on update CURRENT_TIMESTAMP".
     * This is usually used for MySQL `timestamp` and `datetime`.
     */
    public function isOnUpdateCurrentTimestamp(): bool
    {
        return $this->onUpdateCurrentTimestamp;
    }

    /**
     * Check if default should set as raw.
     * Raw default will be generated with DB::raw().
     */
    public function isRawDefault(): bool
    {
        return $this->rawDefault;
    }

    /**
     * Get the virtual column definition.
     */
    public function getVirtualDefinition(): ?string
    {
        return $this->virtualDefinition;
    }

    /**
     * Get the stored column definition.
     */
    public function getStoredDefinition(): ?string
    {
        return $this->storedDefinition;
    }


    /**
     * Parse the length from the full definition type.
     */
    protected function parseLength(string $fullDefinitionType): ?int
    {
        switch ($this->type) {
            case MigrationType::CHAR:
            case MigrationType::STRING:
            case MigrationType::DATE:
            case MigrationType::DATETIME:
            case MigrationType::DATETIME_TZ:
            case MigrationType::TIME:
            case MigrationType::TIME_TZ:
            case MigrationType::TIMESTAMP:
            case MigrationType::TIMESTAMP_TZ:
                if (preg_match('/\((\d*)\)/', $fullDefinitionType, $matches) === 1) {
                    return (int) $matches[1];
                }

                break;

            default:
        }

        return null;
    }

    /**
     * Parse the precision and scale from the full definition type.
     *
     * @return array{0: int|null, 1: int}
     */
    protected function parsePrecisionAndScale(string $fullDefinitionType): array
    {
        switch ($this->type) {
            case MigrationType::DECIMAL:
            case MigrationType::DOUBLE:
            case MigrationType::FLOAT:
                if (preg_match('/\((\d+)(?:,\s*(\d+))?\)?/', $fullDefinitionType, $matches) === 1) {
                    return [(int) $matches[1], isset($matches[2]) ? (int) $matches[2] : 0];
                }

                break;

            default:
        }

        return [null, 0];
    }

    /**
     * Get the preset values if the column is "enum".
     *
     * @return string[]
     */
    private function getEnumPresetValues(string $fullDefinition): array
    {
        $value = substr(
            $fullDefinition,
            strlen("enum('"),
            -strlen("')"),
        );
        return explode("','", $value);
    }

    /**
     * Get the preset values if the column is "set".
     *
     * @return string[]
     */
    private function getSetPresetValues(string $fullDefinition): array
    {
        $value = substr(
            $fullDefinition,
            strlen("set('"),
            -strlen("')"),
        );
        return explode("','", $value);
    }

    /**
     * Set the column type to "rememberToken".
     */
    private function setTypeToRememberToken(): void
    {
        if (
            'remember_token' !== $this->name
            || $this->length !== self::REMEMBER_TOKEN_LENGTH
        ) {
            return;
        }

        $this->type = MigrationType::REMEMBER_TOKEN;
    }

    /**
     * Set the column type to "softDeletes" or "softDeletesTz".
     */
    private function setTypeToSoftDeletes(): void
    {
        if ($this->name !== 'deleted_at') {
            return;
        }

        switch ($this->type) {
            case MigrationType::TIMESTAMP:
                $this->type = MigrationType::SOFT_DELETES;
                return;

            case MigrationType::TIMESTAMP_TZ:
                $this->type = MigrationType::SOFT_DELETES_TZ;
                return;

            default:
        }
    }

    /**
     * Set the column type to "unsigned*" if the column is unsigned.
     */
    private function setTypeToUnsigned(): void
    {
        if (
            !in_array($this->type, [
                MigrationType::BIG_INTEGER,
                MigrationType::INTEGER,
                MigrationType::MEDIUM_INTEGER,
                MigrationType::SMALL_INTEGER,
                MigrationType::TINY_INTEGER,
            ])
            || !$this->unsigned
        ) {
            return;
        }

        $this->type = MigrationType::from('unsigned' . ucfirst($this->type->value));
    }

    /**
     * Set the column type to "increments" or "*Increments" if the column is auto increment.
     * If the DB supports unsigned, should check if the column is unsigned.
     */
    protected function setTypeToIncrements(): void
    {
        if (
            !in_array($this->type, [
                MigrationType::BIG_INTEGER,
                MigrationType::INTEGER,
                MigrationType::MEDIUM_INTEGER,
                MigrationType::SMALL_INTEGER,
                MigrationType::TINY_INTEGER,
            ])
        ) {
            return;
        }

        if (!$this->autoincrement) {
            return;
        }

        if (!$this->unsigned) {
            return;
        }

        if ($this->type === MigrationType::INTEGER) {
            $this->type = MigrationType::INCREMENTS;
            return;
        }

        $this->type = MigrationType::from(str_replace('Integer', 'Increments', $this->type->value));
    }

    /**
     * Escape `\` with `\\`.
     */
    protected function escapeDefault(?string $default): ?string
    {
        if ($default === null) {
            return null;
        }

        $default = str_replace("'", "''", $default);

        return addcslashes($default, '\\');
    }

    /**
     * Escape `\` with `\\`.
     */
    protected function escapeComment(?string $comment): ?string
    {
        if ($comment === null || $comment === '') {
            return null;
        }

        return addcslashes($comment, '\\');
    }

    /**
     * Set to geometry or geography.
     */
    private function setRealSpatialColumn(): void
    {
        switch ($this->type) {
            case MigrationType::GEOMETRY_COLLECTION:
                $this->spatialSubType = 'geometryCollection';
                break;

            case MigrationType::LINE_STRING:
                $this->spatialSubType = 'lineString';
                break;

            case MigrationType::MULTI_LINE_STRING:
                $this->spatialSubType = 'multiLineString';
                break;

            case MigrationType::POINT:
                $this->spatialSubType = 'point';
                break;

            case MigrationType::MULTI_POINT:
                $this->spatialSubType = 'multiPoint';
                break;

            case MigrationType::POLYGON:
                $this->spatialSubType = 'polygon';
                break;

            case MigrationType::MULTI_POLYGON:
                $this->spatialSubType = 'multiPolygon';
                break;

            default:
        }

        $this->type = MigrationType::GEOMETRY;

        $this->spatialSrID = $this->getSrID($this->tableName, $this->name);

        if ($this->spatialSrID === null) {
            return;
        }

        $this->type = MigrationType::GEOGRAPHY;
    }

    /**
     * Checks if column has `on update CURRENT_TIMESTAMP`
     */
    private function hasOnUpdateCurrentTimestamp(): bool
    {
        // MySQL5.7 shows "on update CURRENT_TIMESTAMP"
        // MySQL8 shows "DEFAULT_GENERATED on update CURRENT_TIMESTAMP"
        $result = DB::selectOne(
            "SHOW COLUMNS FROM `$this->tableName`
                WHERE Field = '$this->name'
                    AND (Type LIKE 'timestamp%' OR Type LIKE 'datetime%')
                    AND Extra LIKE '%on update CURRENT_TIMESTAMP%'",
        );
        return !($result === null);
    }

    /**
     * Get the SRID by table and column name.
     */
    private function getSrID(string $table, string $column): ?int
    {
        try {
            $srsID = DB::selectOne(
                "SELECT SRS_ID
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = '" . DB::getDatabaseName() . "'
                    AND TABLE_NAME = '" . $table . "'
                    AND COLUMN_NAME = '" . $column . "'",
            );
        } catch (QueryException $exception) {
            if (
                // `SRS_ID` available since MySQL 8.0.3.
                // https://dev.mysql.com/doc/relnotes/mysql/8.0/en/news-8-0-3.html
            Str::contains(
                $exception->getMessage(),
                "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'SRS_ID'",
                true,
            )
            ) {
                return null;
            }

            throw $exception;
        }

        if ($srsID === null) {
            return null;
        }

        $srsIDArr = array_change_key_case((array) $srsID);
        return $srsIDArr['srs_id'] ?? null;
    }

    /**
     * Set virtual definition if the column is virtual.
     */
    private function setVirtualDefinition(): void
    {
        if ($this->virtualDefinition === null) {
            return;
        }

        // The definition of MySQL8 returned `concat(string,_utf8mb4\' \',string_255)`.
        // Replace `\'` to `'` here to avoid double escape.
        $this->virtualDefinition = str_replace("\'", "'", $this->virtualDefinition);
    }

    /**
     * Set stored definition if the column is stored.
     */
    private function setStoredDefinition(): void
    {
        if ($this->storedDefinition === null) {
            return;
        }

        // The definition of MySQL8 returned `concat(string,_utf8mb4\' \',string_255)`.
        // Replace `\'` to `'` here to avoid double escape.
        $this->storedDefinition = str_replace("\'", "'", $this->storedDefinition);
    }

    /**
     * Set the default value to `CURRENT_TIMESTAMP` if the value is `CURRENT_TIMESTAMP(2)`, `CURRENT_TIMESTAMP(3)`, ... `CURRENT_TIMESTAMP(n)`.
     * This function is needed so that
     * column type `datetime(2) DEFAULT CURRENT_TIMESTAMP(2)` would be generated as
     * `dateTime('datetime', 2)->useCurrent()`.
     */
    private function flattenCurrentTimestamp(): void
    {
        if ($this->default === null) {
            return;
        }

        if (!Str::startsWith($this->default, 'CURRENT_TIMESTAMP')) {
            return;
        }

        $this->default = 'CURRENT_TIMESTAMP';
    }

}