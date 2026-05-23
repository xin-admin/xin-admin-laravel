<?php

namespace Modules\SystemTool\Attributes;

use Attribute;
use Modules\SystemTool\Enum\ESettingType;

/**
 * 在 SettingsDefinition 子类上声明配置项
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Setting
{
    public function __construct(
        public string       $config,
        public ESettingType $type = ESettingType::String,
        public ?string      $description = null,
    ) {}
}
