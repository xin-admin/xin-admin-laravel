import { FormRule } from 'antd';
import * as verify from '@/utils/format';

export const buildValueEnum = (data: OnlineType.ColumnsConfig) => {
  let map = new Map;
  if (['select', 'checkbox', 'radio', 'radioButton'].includes(data.valueType!)) {
    let enumArr = data.enum!.split('\n');
    enumArr.forEach((str: string) => {
      let data = str.split(':');
      map.set(data[0], data[1]);
    });
    return map;
  }else {
    return false;
  }
};

export const buildValidation = (data: OnlineType.ColumnsConfig) => {
  let rules: FormRule[] = [];
  if (data.validation instanceof Array && data.validation?.length > 0) {
    data.validation.forEach((rule) => {
      if (rule in verify) {
        rules.push(verify[rule as keyof typeof verify]);
      }
    });
    return rules;
  }else {
    return false
  }
}

export const buildColumns = (dataSource: any) => {
  return dataSource.map((item: OnlineType.ColumnsConfig) => {
    let data: any = {...item}
    // 生成枚举
    data.key = item.dataIndex;
    let valueEnum = buildValueEnum(item)
    if(valueEnum) data.valueEnum = valueEnum
    // 处理验证规则
    let rules =  buildValidation(item)
    if(rules) data.formItemProps = { ...data.formItemProps,rules };
    return data;
  });
}
