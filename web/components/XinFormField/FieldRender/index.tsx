import type { FormColumn } from "./typings";
import {
  Input,
  InputNumber,
  Select,
  Radio,
  Checkbox,
  Switch,
  DatePicker,
  TimePicker,
  TreeSelect,
  Cascader,
  Rate,
  Slider,
  ColorPicker
} from 'antd';
import type {
  InputProps,
  InputNumberProps,
  SelectProps,
  TreeSelectProps,
  RadioGroupProps,
  SwitchProps,
  RateProps,
  SliderSingleProps,
  DatePickerProps,
  TimePickerProps,
  ColorPickerProps
} from 'antd';
import type { PasswordProps, TextAreaProps } from "antd/es/input";
import type { RangePickerProps } from "antd/es/date-picker";
import type { CheckboxGroupProps } from 'antd/es/checkbox';
import IconSelector from '@/components/XinFormField/IconSelector';
import ImageUploader from '@/components/XinFormField/ImageUploader';
import UserSelector from '@/components/XinFormField/UserSelector';
import type { IconSelectProps } from '@/components/XinFormField/IconSelector/typings';
import type { ImageUploaderProps } from '@/components/XinFormField/ImageUploader/typings';
import type { UserSelectorProps } from '@/components/XinFormField/UserSelector/typings';
import type {ReactNode} from "react";

const { TextArea, Password } = Input;
const { RangePicker } = DatePicker;

interface FieldsRenderProps<T> extends Record<string, any> {
  valueType: FormColumn<T>['valueType'];
}

export default function FieldRender<T>(props: FieldsRenderProps<T>) {
  const { valueType, ...fieldProps } = props;
  let dom: ReactNode;
  switch (valueType) {
    case 'password':
      dom = <Password {...fieldProps as PasswordProps}/>;
      break;
    case 'textarea':
      dom =  <TextArea rows={4} {...fieldProps as TextAreaProps} />;
      break;
    case 'digit':
      dom =  <InputNumber style={{ width: '100%' }} {...fieldProps as InputNumberProps} />;
      break;
    case 'money':
      dom =  <InputNumber style={{ width: '100%' }} precision={2} prefix="¥" {...fieldProps as InputNumberProps} />;
      break;
    case 'select':
      dom =  <Select {...fieldProps as SelectProps} />;
      break;
    case 'treeSelect':
      dom =  <TreeSelect {...fieldProps as TreeSelectProps} />;
      break;
    case 'cascader':
      dom =  <Cascader {...fieldProps as any} />;
      break;
    case 'radio':
      dom =  <Radio.Group options={[]} optionType="default" {...fieldProps as RadioGroupProps} />;
      break;
    case 'radioButton':
      dom =  <Radio.Group options={[]} optionType="button" {...fieldProps as RadioGroupProps} />;
      break;
    case 'checkbox':
      dom =  <Checkbox.Group options={[]} {...fieldProps as CheckboxGroupProps} />;
      break;
    case 'switch':
      dom =  <Switch {...fieldProps as SwitchProps} />;
      break;
    case 'rate':
      dom =  <Rate {...fieldProps as RateProps} />;
      break;
    case 'slider':
      dom =  <Slider {...fieldProps as SliderSingleProps} />;
      break;
    case 'date':
      dom =  <DatePicker style={{ width: '100%' }} {...fieldProps as DatePickerProps} />;
      break;
    case 'dateTime':
      dom =  <DatePicker style={{ width: '100%' }} showTime {...fieldProps as DatePickerProps} />;
      break;
    case 'dateRange':
      dom =  <RangePicker style={{ width: '100%' }} {...fieldProps as RangePickerProps} />;
      break;
    case 'time':
      dom =  <TimePicker style={{ width: '100%' }} {...fieldProps as TimePickerProps} />;
      break;
    case 'timeRange':
      dom =  <TimePicker.RangePicker style={{ width: '100%' }} {...fieldProps as RangePickerProps} />;
      break;
    case 'week':
      dom =  <DatePicker style={{ width: '100%' }} picker="week" {...fieldProps as DatePickerProps} />;
      break;
    case 'month':
      dom =  <DatePicker style={{ width: '100%' }} picker="month" {...fieldProps as DatePickerProps} />;
      break;
    case 'quarter':
      dom =  <DatePicker style={{ width: '100%' }} picker="quarter" {...fieldProps as DatePickerProps} />;
      break;
    case 'year':
      dom =  <DatePicker style={{ width: '100%' }} picker="year" {...fieldProps as DatePickerProps} />;
      break;
    case 'color':
      dom =  <ColorPicker {...fieldProps as ColorPickerProps} />;
      break;
    case 'image':
      dom =  <ImageUploader {...fieldProps as ImageUploaderProps} />;
      break;
    case 'icon':
      dom =  <IconSelector {...fieldProps as IconSelectProps} />;
      break;
    case 'user':
      dom =  <UserSelector {...fieldProps as UserSelectorProps} />;
      break;
    case 'text':
    default:
      dom =  <Input {...fieldProps as InputProps} />;
      break;
  }
  return dom;
}

export type { FieldsRenderProps, FormColumn };
