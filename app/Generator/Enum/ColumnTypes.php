<?php

namespace App\Generator\Enum;

use App\Generator\Column;
use Illuminate\Database\Schema\Blueprint;

enum ColumnTypes: string
{
    // 布尔类型
    case BOOLEAN = 'boolean';

    // 字符串类型
    case CHAR = 'char';
    case MEDIUM_TEXT = 'mediumText';
    case TEXT = 'text';
    case LONG_TEXT = 'longText';
    case TINY_TEXT = 'tinyText';
    case STRING = 'string';

    // 数字类型
    case BIGINCREMENTS = 'bigIncrements';
    case INTEGERS = 'integers';
    case BIGINTEGER = 'bigInteger';
    case DECIMAL = 'decimal';
    case DOUBLE = 'double';
    case FLOAT = 'float';
    case ID = 'id';
    case INCREMENTS = 'increments';
    case INTEGER = 'integer';
    case MEDIUMINCREMENTS = 'mediumIncrements';
    case MEDIUMINTEGER = 'mediumInteger';
    case SMALLINCREMENTS = 'smallIncrements';
    case SMALLINTEGER = 'smallInteger';
    case TINYINCREMENTS = 'tinyIncrements';
    case TINYINTEGER = 'tinyInteger';
    case UNSIGNEDBIGINTEGER = 'unsignedBigInteger';
    case UNSIGNEDINTEGER = 'unsignedInteger';
    case UNSIGNEDMEDIUMINTEGER = 'unsignedMediumInteger';
    case UNSIGNEDSMALLINTEGER = 'unsignedSmallInteger';
    case UNSIGNEDTINYINTEGER = 'unsignedTinyInteger';

    // 日期时间类型
    case DATETIME = 'dateTime';
    case DATETIMETZ = 'dateTimeTz';
    case DATE = 'date';
    case TIME = 'time';
    case TIMETZ = 'timeTz';
    case TIMESTAMP = 'timestamp';
    case TIMESTAMPS = 'timestamps';
    case TIMESTAMPSTZ = 'timestampsTz';
    case SOFTDELETES = 'softDeletes';
    case SOFTDELETESTZ = 'softDeletesTz';
    case YEAR = 'year';

    // 二进制和JSON类型
    case BINARY = 'binary';
    case JSON = 'json';
    case JSONB = 'jsonb';

    // UUID和ULID类型
    case ULID = 'ulid';
    case ULIDMORPHS = 'ulidMorphs';
    case UUID = 'uuid';
    case UUIDMORPHS = 'uuidMorphs';
    case NULLABLEULIDMORPHS = 'nullableUlidMorphs';
    case NULLABLEUUIDMORPHS = 'nullableUuidMorphs';

    // 地理空间类型
    case GEOGRAPHY = 'geography';
    case GEOMETRY = 'geometry';

    // 外键类型
    case FOREIGNID = 'foreignId';
    case FOREIGNIDFOR = 'foreignIdFor';
    case FOREIGNULID = 'foreignUlid';
    case FOREIGNUUID = 'foreignUuid';

    // 多态关联类型
    case MORPHS = 'morphs';
    case NULLABLEMORPHS = 'nullableMorphs';

    // 枚举和集合类型
    case ENUM = 'enum';
    case SET = 'set';

    // 网络类型
    case MACADDRESS = 'macAddress';
    case IPADDRESS = 'ipAddress';

    // 其他类型
    case REMEMBERTOKEN = 'rememberToken';
    case VECTOR = 'vector';

    public static function toMigration(Column $column)
    {
        $columnType = self::from($column->getType());

    }

    public static function toString(Column $column): string
    {
        return '';
    }
}