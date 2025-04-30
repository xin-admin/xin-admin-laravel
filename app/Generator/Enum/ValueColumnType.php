<?php

namespace App\Generator\Enum;

use ReflectionEnumBackedCase;
use App\Generator\Enum\SqlColumnType as ST;
use App\Generator\Mapping\SqlMapping as S;

enum ValueColumnType: string implements ColumnEnum
{
    #[S(ST::STRING)]
    case PASSWORD       = 'password';

    #[S(ST::DECIMAL)]
    case MONEY          = 'money';

    #[S(ST::TEXT)]
    case TEXTAREA       = 'textarea';

    #[S(ST::DATE)]
    case DATE           = 'date';

    #[S(ST::DATETIME)]
    case DATE_TIME      = 'dateTime';

    #[S(ST::DATE)]
    case DATE_WEEK      = 'dateWeek';

    #[S(ST::DATE)]
    case DATE_MONTH     = 'dateMonth';

    #[S(ST::DATE)]
    case DATE_QUARTER   = 'dateQuarter';

    #[S(ST::DATE)]
    case DATE_YEAR      = 'dateYear';

    case DATE_RANGE     = 'dateRange';
    case DATE_TIME_RANGE = 'dateTimeRange';

    #[S(ST::TIME)]
    case TIME           = 'time';

    case TIME_RANGE     = 'timeRange';

    #[S(ST::STRING)]
    case TEXT           = 'text';

    #[S(ST::ENUM)]
    case SELECT         = 'select';

    case TREE_SELECT    = 'treeSelect';

    #[S(ST::SET)]
    case CHECKBOX       = 'checkbox';

    #[S(ST::DECIMAL)]
    case RATE           = 'rate';

    #[S(ST::ENUM)]
    case RADIO          = 'radio';

    #[S(ST::ENUM)]
    case RADIO_BUTTON   = 'radioButton';

    #[S(ST::INT)]
    case PROGRESS       = 'progress';

    #[S(ST::INT)]
    case PERCENT        = 'percent';

    #[S(ST::INT)]
    case DIGIT          = 'digit';

    case SECOND         = 'second';

    #[S(ST::STRING)]
    case AVATAR         = 'avatar';

    #[S(ST::TEXT)]
    case CODE           = 'code';

    #[S(ST::TINY_INT)]
    case SWITCH         = 'switch';

    case FROM_NOW       = 'fromNow';

    case IMAGE          = 'image';

    #[S(ST::JSON)]
    case JSON_CODE      = 'jsonCode';

    case COLOR          = 'color';

    public function toSql(): SqlColumnType
    {
        $reflection = new ReflectionEnumBackedCase(self::class, $this->name);
        $attributes = $reflection->getAttributes(S::class);

        if ($attributes) {
            /** @var S $attribute */
            $attribute = $attributes[0]->newInstance();
            return $attribute->type;
        }

        // 默认返回 STRING 类型，防止未映射的类型出错
        return ST::STRING;
    }
}