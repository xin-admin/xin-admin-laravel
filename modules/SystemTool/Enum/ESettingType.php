<?php

namespace Modules\SystemTool\Enum;

/**
 * 设置类型枚举
 *
 * 对应 应用设置 表的 type 列，决定值存储在 s / n / e 哪个字段。
 */
enum ESettingType: int
{
    case String = 10;
    case Bool = 15;
    case Number = 20;
    case Array = 30;
    case Object = 40;
    case EncryptedString = 50;

    /**
     * 是否为字符串类型
     */
    public function isString(): bool
    {
        return $this === self::String;
    }

    /**
     * 是否为数字/布尔类型（存储在 n 列）
     */
    public function isNumeric(): bool
    {
        return in_array($this, [self::Bool, self::Number], true);
    }

    /**
     * 是否为扩展类型（存储在 e 列）
     */
    public function isExtended(): bool
    {
        return in_array($this, [self::Array, self::Object, self::EncryptedString], true);
    }
}
