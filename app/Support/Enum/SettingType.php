<?php

namespace App\Support\Enum;

/**
 * 系统设置类型枚举
 */
enum SettingType: string
{
    // 前端表单组件类型（新版，与前端保持一致）
    case INPUT = 'Input';                // 输入框
    case TEXTAREA = 'TextArea';          // 文本域
    case INPUT_NUMBER = 'InputNumber';   // 数字输入框
    case SWITCH = 'Switch';              // 开关
    case RADIO = 'Radio';                // 单选框
    case CHECKBOX = 'Checkbox';          // 复选框
    case SELECT = 'Select';              // 下拉选择
    case DATE_PICKER = 'DatePicker';     // 日期选择器

    /**
     * 获取类型说明
     */
    public function label(): string
    {
        return match($this) {
            self::INPUT => '输入框',
            self::TEXTAREA => '文本域',
            self::INPUT_NUMBER => '数字输入框',
            self::SWITCH => '开关',
            self::RADIO => '单选框',
            self::CHECKBOX => '复选框',
            self::SELECT => '下拉选择',
            self::DATE_PICKER => '日期选择器',
        };
    }

    /**
     * 判断是否为数字类型
     */
    public function isNumeric(): bool
    {
        return match($this) {
            self::INPUT_NUMBER => true,
            default => false,
        };
    }

    /**
     * 判断是否为布尔类型
     */
    public function isBoolean(): bool
    {
        return match($this) {
            self::SWITCH => true,
            default => false,
        };
    }

    /**
     * 判断是否为数组类型
     */
    public function isArray(): bool
    {
        return match($this) {
            self::CHECKBOX => true,
            default => false,
        };
    }

    /**
     * 获取所有前端组件类型
     */
    public static function getFrontendTypes(): array
    {
        return [
            self::INPUT->value,
            self::TEXTAREA->value,
            self::INPUT_NUMBER->value,
            self::SWITCH->value,
            self::RADIO->value,
            self::CHECKBOX->value,
            self::SELECT->value,
            self::DATE_PICKER->value,
        ];
    }

    /**
     * 从字符串创建枚举实例（宽松匹配）
     */
    public static function fromString(string $type): ?self
    {
        return self::tryFrom($type);
    }
}
