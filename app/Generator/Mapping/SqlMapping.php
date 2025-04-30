<?php

namespace App\Generator\Mapping;

use App\Generator\Enum\SqlColumnType;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class SqlMapping
{
    public function __construct(
        public SqlColumnType $type
    ) {}
}