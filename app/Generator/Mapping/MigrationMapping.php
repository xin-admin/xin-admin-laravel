<?php

namespace App\Generator\Mapping;

use App\Generator\Enum\MigrationColumnType;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class MigrationMapping
{
    public function __construct(
        public MigrationColumnType $type
    )
    {
    }
}
