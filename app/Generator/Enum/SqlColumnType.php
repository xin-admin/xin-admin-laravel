<?php

namespace App\Generator\Enum;


/**
 * Define column types of sql.
 *
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
}
