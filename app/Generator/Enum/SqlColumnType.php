<?php

namespace App\Generator\Enum;

/**
 * Define column types of sql.
 */
enum SqlColumnType: string implements ColumnEnum
{
    case BIGINT = 'bigint';
    case BINARY = 'binary';
    case BIT = 'bit';
    case BLOB = 'blob';
    case CHAR = 'char';
    case DATE = 'date';
    case DATETIME = 'datetime';
    case DECIMAL = 'decimal';
    case DOUBLE = 'double';
    case ENUM = 'enum';
    case FLOAT = 'float';
    case GEOGRAPHY = 'geography';
    case GEOMETRY = 'geometry';
    case INT = 'int';
    case INTEGER = 'integer';
    case JSON = 'json';
    case LONG_BLOB = 'longblob';
    case LONG_TEXT = 'longtext';
    case MEDIUM_BLOB = 'mediumblob';
    case MEDIUM_INT = 'mediumint';
    case MEDIUM_TEXT = 'mediumtext';
    case NUMERIC = 'numeric';
    case REAL = 'real';
    case SET = 'set';
    case SMALL_INT = 'smallint';
    case STRING = 'string';
    case TEXT = 'text';
    case TIME = 'time';
    case TIMESTAMP = 'timestamp';
    case TINY_BLOB = 'tinyblob';
    case TINY_INT = 'tinyint';
    case TINY_TEXT = 'tinytext';
    case VARBINARY = 'varbinary';
    case VARCHAR = 'varchar';
    case YEAR = 'year';

    case GEOM_COLLECTION = 'geomcollection';
    case LINE_STRING = 'linestring';
    case MULTI_LINE_STRING = 'multilinestring';
    case POINT = 'point';
    case MULTI_POINT = 'multipoint';
    case POLYGON = 'polygon';
    case MULTI_POLYGON = 'multipolygon';

    // For MariaDB
    case UUID = 'uuid';

    // For MariaDB and MySQL57
    case GEOMETRY_COLLECTION = 'geometrycollection';

    public function toMigration(): MigrationType
    {
        return match ($this) {
            self::BIGINT => MigrationType::BIG_INTEGER,
            self::BINARY,
            self::BLOB,
            self::LONG_BLOB,
            self::MEDIUM_BLOB,
            self::TINY_BLOB,
            self::VARBINARY => MigrationType::BINARY,
            self::BIT => MigrationType::BOOLEAN,
            self::CHAR => MigrationType::CHAR,
            self::DATE => MigrationType::DATE,
            self::DATETIME => MigrationType::DATETIME,
            self::DECIMAL,
            self::NUMERIC => MigrationType::DECIMAL,
            self::DOUBLE => MigrationType::DOUBLE,
            self::ENUM => MigrationType::ENUM,
            self::FLOAT,
            self::REAL => MigrationType::FLOAT,
            self::GEOGRAPHY => MigrationType::GEOGRAPHY,
            self::GEOMETRY,
            self::GEOM_COLLECTION,
            self::LINE_STRING,
            self::MULTI_LINE_STRING,
            self::POINT,
            self::MULTI_POINT,
            self::POLYGON,
            self::MULTI_POLYGON,
            self::GEOMETRY_COLLECTION => MigrationType::GEOMETRY,
            self::INT,
            self::INTEGER => MigrationType::INTEGER,
            self::JSON => MigrationType::JSON,
            self::LONG_TEXT => MigrationType::LONG_TEXT,
            self::MEDIUM_INT => MigrationType::MEDIUM_INTEGER,
            self::MEDIUM_TEXT => MigrationType::MEDIUM_TEXT,
            self::SET => MigrationType::SET,
            self::SMALL_INT => MigrationType::SMALL_INTEGER,
            self::STRING,
            self::VARCHAR => MigrationType::STRING,
            self::TEXT => MigrationType::TEXT,
            self::TIME => MigrationType::TIME,
            self::TIMESTAMP => MigrationType::TIMESTAMP,
            self::TINY_INT => MigrationType::TINY_INTEGER,
            self::TINY_TEXT => MigrationType::TINY_TEXT,
            self::YEAR => MigrationType::YEAR,
            self::UUID => MigrationType::UUID,
        };
    }

    public function toValueColumnType(): ValueColumnType
    {
        return match ($this) {
            self::BIGINT,
            self::INT,
            self::INTEGER,
            self::MEDIUM_INT,
            self::SMALL_INT,
            self::TINY_INT => ValueColumnType::DIGIT,
            self::DECIMAL,
            self::DOUBLE,
            self::FLOAT,
            self::NUMERIC,
            self::REAL => ValueColumnType::MONEY,
            self::CHAR,
            self::STRING,
            self::VARCHAR,
            self::UUID => ValueColumnType::TEXT,
            self::TEXT,
            self::LONG_TEXT,
            self::MEDIUM_TEXT,
            self::TINY_TEXT,
            self::BINARY,
            self::VARBINARY,
            self::BLOB,
            self::LONG_BLOB,
            self::GEOMETRY,
            self::GEOM_COLLECTION,
            self::LINE_STRING,
            self::MULTI_LINE_STRING,
            self::POINT,
            self::MULTI_POINT,
            self::POLYGON,
            self::MULTI_POLYGON,
            self::GEOGRAPHY,
            self::MEDIUM_BLOB,
            self::TINY_BLOB,
            self::GEOMETRY_COLLECTION => ValueColumnType::TEXTAREA,
            self::DATE => ValueColumnType::DATE,
            self::DATETIME,
            self::TIMESTAMP => ValueColumnType::DATE_TIME,
            self::TIME => ValueColumnType::TIME,
            self::ENUM => ValueColumnType::RADIO,
            self::SET => ValueColumnType::CHECKBOX,
            self::BIT => ValueColumnType::SWITCH,
            self::JSON => ValueColumnType::JSON_CODE,
            self::YEAR => ValueColumnType::DATE_YEAR,
        };
    }
}