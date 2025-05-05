<?php

namespace App\Generator\Column;

use App\Generator\Enum\MigrationColumnType;

class MySQLColumnType
{
    /**
     * @var array<string, MigrationColumnType>
     */
    protected static array $map = [
        'bigint'             => [
            'migration' => MigrationColumnType::BIG_INTEGER,
            'request'   => MigrationColumnType::BIG_INTEGER,
        ],
        'binary' => [
            'migration' => MigrationColumnType::BINARY,
        ],
        'bit' => [
            'migration' => MigrationColumnType::BOOLEAN,
        ],
        'blob' => [
            'migration' => MigrationColumnType::BINARY,
        ],
        'char' => [
            'migration' => MigrationColumnType::CHAR,
        ],
        'date' => [
            'migration' => MigrationColumnType::DATE,
        ],
        'datetime' => [
            'migration' => MigrationColumnType::DATETIME,
        ],
        'decimal' => [
            'migration' => MigrationColumnType::DECIMAL,
        ],
        'double' => [
            'migration' => MigrationColumnType::DOUBLE,
        ],
        'enum' => [
            'migration' => MigrationColumnType::ENUM,
        ],
        'float' => [
            'migration' => MigrationColumnType::FLOAT,
        ],
        'geography' => [
            'migration' => MigrationColumnType::GEOGRAPHY,
        ],
        'geometry' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],
        'int' => [
            'migration' => MigrationColumnType::INTEGER,
        ],
        'integer' => [
            'migration' => MigrationColumnType::INTEGER,
        ],
        'json' => [
            'migration' => MigrationColumnType::JSON,
        ],
        'longblob' => [
            'migration' => MigrationColumnType::BINARY,
        ],
        'longtext' => [
            'migration' => MigrationColumnType::LONG_TEXT,
        ],
        'mediumblob' => [
            'migration' => MigrationColumnType::BINARY,
        ],
        'mediumint' => [
            'migration' => MigrationColumnType::MEDIUM_INTEGER,
        ],
        'mediumtext' => [
            'migration' => MigrationColumnType::MEDIUM_TEXT,
        ],
        'numeric' => [
            'migration' => MigrationColumnType::DECIMAL,
        ],
        'real' => [
            'migration' => MigrationColumnType::FLOAT,
        ],
        'set' => [
            'migration' => MigrationColumnType::SET,
        ],
        'smallint' => [
            'migration' => MigrationColumnType::SMALL_INTEGER,
        ],
        'string' => [
            'migration' => MigrationColumnType::STRING,
        ],
        'text' => [
            'migration' => MigrationColumnType::TEXT,
        ],
        'time' => [
            'migration' => MigrationColumnType::TIME,
        ],
        'timestamp' => [
            'migration' => MigrationColumnType::TIMESTAMP,
        ],
        'tinyblob' => [
            'migration' => MigrationColumnType::BINARY,
        ],
        'tinyint' => [
            'migration' => MigrationColumnType::TINY_INTEGER,
        ],
        'tinytext' => [
            'migration' => MigrationColumnType::TINY_TEXT,
        ],
        'varbinary' => [
            'migration' => MigrationColumnType::BINARY,
        ],
        'varchar' => [
            'migration' => MigrationColumnType::STRING,
        ],
        'year' => [
            'migration' => MigrationColumnType::YEAR,
        ],

        // Geometry types
        'geomcollection' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],
        'linestring' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],
        'multilinestring' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],
        'point' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],
        'multipoint' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],
        'polygon' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],
        'multipolygon' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],

        // MariaDB specific
        'uuid' => [
            'migration' => MigrationColumnType::UUID,
        ],

        // MariaDB and MySQL 5.7+
        'geometrycollection' => [
            'migration' => MigrationColumnType::GEOMETRY,
        ],
    ];

    public static function toMigration(string $dbType): MigrationColumnType
    {
        return self::$map[strtolower($dbType)]['migration'] ?? MigrationColumnType::STRING;
    }
}
