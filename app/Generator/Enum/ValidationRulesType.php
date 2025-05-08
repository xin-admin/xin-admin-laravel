<?php

namespace App\Generator\Enum;

/**
 * Define column types of the validation rules.
 *
 * @link https://laravel.com/docs/master/validation#available-validation-rules
 */
enum ValidationRulesType: string implements ColumnEnum
{
    case ACCEPTED = 'accepted';
    case ACCEPTED_IF = 'accepted_if';
    case ACTIVE_URL = 'active_url';
    case AFTER = 'after';
    case AFTER_OR_EQUAL = 'after_or_equal';
    case ANY_OF = 'any_of';
    case ALPHA = 'alpha';
    case ALPHA_DASH = 'alpha_dash';
    case ALPHA_NUM = 'alpha_num';
    case ARRAY = 'array';
    case ASCII = 'ascii';
    case BAIL = 'bail';
    case BEFORE = 'before';
    case BEFORE_OR_EQUAL = 'before_or_equal';
    case BETWEEN = 'between';
    case BOOLEAN = 'boolean';
    case CONTAINS = 'contains';
    case CONFIRMED = 'confirmed';
    case CURRENT_PASSWORD = 'current_password';
    case DATE = 'date';
    case DATE_EQUALS = 'date_equals';
    case DATE_FORMAT = 'date_format';
    case DECIMAL = 'decimal';
    case DECLINED = 'declined';
    case DECLINED_IF = 'declined_if';
    case DIFFERENT = 'different';
    case DIGITS = 'digits';
    case DIGITS_BETWEEN = 'digits_between';
    case DIMENSIONS = 'dimensions';
    case DISTINCT = 'distinct';
    case DOESNT_END_WITH = 'doesnt_end_with';
    case DOESNT_START_WITH = 'doesnt_start_with';
    case EMAIL = 'email';
    case ENDS_WITH = 'ends_with';
    case ENUM = 'enum';
    case EXCLUDE = 'exclude';
    case EXCLUDE_IF = 'exclude_if';
    case EXCLUDE_UNLESS = 'exclude_unless';
    case EXCLUDE_WITH = 'exclude_with';
    case EXCLUDE_WITHOUT = 'exclude_without';
    case EXISTS = 'exists';
    case EXTENSIONS = 'extensions';
    case FILE = 'file';
    case FILLED = 'filled';
    case GREATER_THAN = 'gt';
    case GREATER_THAN_OR_EQUAL = 'gte';
    case HEX_COLOR = 'hex_color';
    case IMAGE = 'image';
    case IN = 'in';
    case IN_ARRAY = 'in_array';
    case INTEGER = 'integer';
    case IP = 'ip';
    case IPV4 = 'ipv4';
    case IPV6 = 'ipv6';
    case JSON = 'json';
    case LESS_THAN = 'lt';
    case LESS_THAN_OR_EQUAL = 'lte';
    case LOWERCASE = 'lowercase';
    case LIST_ = 'list';
    case MAC_ADDRESS = 'mac_address';
    case MAX = 'max';
    case MAX_DIGITS = 'max_digits';
    case MIMETYPES = 'mimetypes';
    case MIMES = 'mimes';
    case MIN = 'min';
    case MIN_DIGITS = 'min_digits';
    case MULTIPLE_OF = 'multiple_of';
    case MISSING = 'missing';
    case MISSING_IF = 'missing_if';
    case MISSING_UNLESS = 'missing_unless';
    case MISSING_WITH = 'missing_with';
    case MISSING_WITH_ALL = 'missing_with_all';
    case NOT_IN = 'not_in';
    case NOT_REGEX = 'not_regex';
    case NULLABLE = 'nullable';
    case NUMERIC = 'numeric';
    case PRESENT = 'present';
    case PRESENT_IF = 'present_if';
    case PRESENT_UNLESS = 'present_unless';
    case PRESENT_WITH = 'present_with';
    case PRESENT_WITH_ALL = 'present_with_all';
    case PROHIBITED = 'prohibited';
    case PROHIBITED_IF = 'prohibited_if';
    case PROHIBITED_IF_ACCEPTED = 'prohibited_if_accepted';
    case PROHIBITED_IF_DECLINED = 'prohibited_if_declined';
    case PROHIBITED_UNLESS = 'prohibited_unless';
    case PROHIBITS = 'prohibits';
    case REGEX = 'regex';
    case REQUIRED = 'required';
    case REQUIRED_IF = 'required_if';
    case REQUIRED_IF_ACCEPTED = 'required_if_accepted';
    case REQUIRED_IF_DECLINED = 'required_if_declined';
    case REQUIRED_UNLESS = 'required_unless';
    case REQUIRED_WITH = 'required_with';
    case REQUIRED_WITH_ALL = 'required_with_all';
    case REQUIRED_WITHOUT = 'required_without';
    case REQUIRED_WITHOUT_ALL = 'required_without_all';
    case REQUIRED_ARRAY_KEYS = 'required_array_keys';
    case SAME = 'same';
    case SIZE = 'size';
    case STARTS_WITH = 'starts_with';
    case STRING = 'string';
    case TIMEZONE = 'timezone';
    case UNIQUE = 'unique';
    case UPPERCASE = 'uppercase';
    case URL = 'url';
    case UUID = 'uuid';
    case ULID = 'ulid';

    case SOMETIMES = 'sometimes';
}