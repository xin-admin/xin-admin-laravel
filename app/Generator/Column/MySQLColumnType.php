<?php

namespace App\Generator\Column;

use App\Generator\Enum\MigrationType;

class MySQLColumnType
{
    /**
     * @var array<string, MigrationType>
     */
    protected static array $map = [
        'bigint'             => MigrationType::BIG_INTEGER,
        'binary'             => MigrationType::BINARY,
        'bit'                => MigrationType::BOOLEAN,
        'blob'               => MigrationType::BINARY,
        'char'               => MigrationType::CHAR,
        'date'               => MigrationType::DATE,
        'datetime'           => MigrationType::DATETIME,
        'decimal'            => MigrationType::DECIMAL,
        'double'             => MigrationType::DOUBLE,
        'enum'               => MigrationType::ENUM,
        'float'              => MigrationType::FLOAT,
        'geography'          => MigrationType::GEOGRAPHY,
        'geometry'           => MigrationType::GEOMETRY,
        'int'                => MigrationType::INTEGER,
        'integer'            => MigrationType::INTEGER,
        'json'               => MigrationType::JSON,
        'longblob'           => MigrationType::BINARY,
        'longtext'           => MigrationType::LONG_TEXT,
        'mediumblob'         => MigrationType::BINARY,
        'mediumint'          => MigrationType::MEDIUM_INTEGER,
        'mediumtext'         => MigrationType::MEDIUM_TEXT,
        'numeric'            => MigrationType::DECIMAL,
        'real'               => MigrationType::FLOAT,
        'set'                => MigrationType::SET,
        'smallint'           => MigrationType::SMALL_INTEGER,
        'string'             => MigrationType::STRING,
        'text'               => MigrationType::TEXT,
        'time'               => MigrationType::TIME,
        'timestamp'          => MigrationType::TIMESTAMP,
        'tinyblob'           => MigrationType::BINARY,
        'tinyint'            => MigrationType::TINY_INTEGER,
        'tinytext'           => MigrationType::TINY_TEXT,
        'varbinary'          => MigrationType::BINARY,
        'varchar'            => MigrationType::STRING,
        'year'               => MigrationType::YEAR,

        'geomcollection'     => MigrationType::GEOMETRY,
        'linestring'         => MigrationType::GEOMETRY,
        'multilinestring'    => MigrationType::GEOMETRY,
        'point'              => MigrationType::GEOMETRY,
        'multipoint'         => MigrationType::GEOMETRY,
        'polygon'            => MigrationType::GEOMETRY,
        'multipolygon'       => MigrationType::GEOMETRY,

        // For MariaDB
        'uuid'               => MigrationType::UUID,

        // For MariaDB and MySQL57
        'geometrycollection' => MigrationType::GEOMETRY,
    ];

    public static function toMigration(string $dbType): MigrationType
    {
        return self::$map[strtolower($dbType)] ?? MigrationType::STRING;
    }
}
