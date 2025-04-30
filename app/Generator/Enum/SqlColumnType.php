<?php

namespace App\Generator\Enum;

use App\Generator\Enum\MigrationColumnType as MT;
use App\Generator\Mapping\MigrationMapping as M;
use ReflectionEnumBackedCase;

enum SqlColumnType: string implements ColumnEnum
{
    #[M(MT::BIG_INTEGER)]
    case BIGINT = 'bigint';

    #[M(MT::BINARY)]
    case BINARY = 'binary';

    #[M(MT::BOOLEAN)]
    case BIT = 'bit';

    #[M(MT::BINARY)]
    case BLOB = 'blob';

    #[M(MT::CHAR)]
    case CHAR = 'char';

    #[M(MT::DATE)]
    case DATE = 'date';

    #[M(MT::DATETIME)]
    case DATETIME = 'datetime';

    #[M(MT::DECIMAL)]
    case DECIMAL = 'decimal';

    #[M(MT::DOUBLE)]
    case DOUBLE = 'double';

    #[M(MT::ENUM)]
    case ENUM = 'enum';

    #[M(MT::FLOAT)]
    case FLOAT = 'float';

    #[M(MT::GEOGRAPHY)]
    case GEOGRAPHY = 'geography';

    #[M(MT::GEOMETRY)]
    case GEOMETRY = 'geometry';

    #[M(MT::INTEGER)]
    case INT = 'int';

    #[M(MT::INTEGER)]
    case INTEGER = 'integer';

    #[M(MT::JSON)]
    case JSON = 'json';

    #[M(MT::BINARY)]
    case LONG_BLOB = 'longblob';

    #[M(MT::LONG_TEXT)]
    case LONG_TEXT = 'longtext';

    #[M(MT::BINARY)]
    case MEDIUM_BLOB = 'mediumblob';

    #[M(MT::MEDIUM_INTEGER)]
    case MEDIUM_INT = 'mediumint';

    #[M(MT::MEDIUM_TEXT)]
    case MEDIUM_TEXT = 'mediumtext';

    #[M(MT::DECIMAL)]
    case NUMERIC = 'numeric';

    #[M(MT::FLOAT)]
    case REAL = 'real';

    #[M(MT::SET)]
    case SET = 'set';

    #[M(MT::SMALL_INTEGER)]
    case SMALL_INT = 'smallint';

    #[M(MT::STRING)]
    case STRING = 'string';

    #[M(MT::TEXT)]
    case TEXT = 'text';

    #[M(MT::TIME)]
    case TIME = 'time';

    #[M(MT::TIMESTAMP)]
    case TIMESTAMP = 'timestamp';

    #[M(MT::BINARY)]
    case TINY_BLOB = 'tinyblob';

    #[M(MT::TINY_INTEGER)]
    case TINY_INT = 'tinyint';

    #[M(MT::TINY_TEXT)]
    case TINY_TEXT = 'tinytext';

    #[M(MT::BINARY)]
    case VARBINARY = 'varbinary';

    #[M(MT::STRING)]
    case VARCHAR = 'varchar';

    #[M(MT::YEAR)]
    case YEAR = 'year';

    // For MariaDB
    #[M(MT::UUID)]
    case UUID = 'uuid';

    // Removed from Laravel v11
    #[M(MT::GEOMETRY_COLLECTION)]
    case GEOM_COLLECTION = 'geomcollection';

    #[M(MT::LINE_STRING)]
    case LINE_STRING = 'linestring';

    #[M(MT::MULTI_LINE_STRING)]
    case MULTI_LINE_STRING = 'multilinestring';

    #[M(MT::POINT)]
    case POINT = 'point';

    #[M(MT::MULTI_POINT)]
    case MULTI_POINT = 'multipoint';

    #[M(MT::POLYGON)]
    case POLYGON = 'polygon';

    #[M(MT::MULTI_POLYGON)]
    case MULTI_POLYGON = 'multipolygon';

    // For MariaDB
    #[M(MT::GEOMETRY_COLLECTION)]
    case GEOMETRY_COLLECTION = 'geometrycollection';


    public function toMigration(): MigrationColumnType
    {
        $reflection = new ReflectionEnumBackedCase(self::class, $this->name);
        $attributes = $reflection->getAttributes(M::class);

        if ($attributes) {
            /** @var M $attribute */
            $attribute = $attributes[0]->newInstance();
            return $attribute->type;
        }

        // 默认返回 STRING 类型，防止未映射的类型出错
        return MT::STRING;
    }
}
