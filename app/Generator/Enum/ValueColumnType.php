<?php

namespace App\Generator\Enum;

/**
 * Define column types of value column.
 *
 * @link https://procomponents.ant.design/components/schema#valuetype-%E5%88%97%E8%A1%A8
 */
enum ValueColumnType: string implements ColumnEnum
{
    case PASSWORD       = 'password';
    case MONEY          = 'money';
    case TEXTAREA       = 'textarea';
    case DATE           = 'date';
    case DATE_TIME      = 'dateTime';
    case DATE_WEEK      = 'dateWeek';
    case DATE_MONTH     = 'dateMonth';
    case DATE_QUARTER   = 'dateQuarter';
    case DATE_YEAR      = 'dateYear';
    case DATE_RANGE     = 'dateRange';
    case DATE_TIME_RANGE = 'dateTimeRange';
    case TIME           = 'time';
    case TIME_RANGE     = 'timeRange';
    case TEXT           = 'text';
    case SELECT         = 'select';
    case TREE_SELECT    = 'treeSelect';
    case CHECKBOX       = 'checkbox';
    case RATE           = 'rate';
    case RADIO          = 'radio';
    case RADIO_BUTTON   = 'radioButton';
    case PROGRESS       = 'progress';
    case PERCENT        = 'percent';
    case DIGIT          = 'digit';
    case SECOND         = 'second';
    case AVATAR         = 'avatar';
    case CODE           = 'code';
    case SWITCH         = 'switch';
    case FROM_NOW       = 'fromNow';
    case IMAGE          = 'image';
    case JSON_CODE      = 'jsonCode';
    case COLOR          = 'color';
}