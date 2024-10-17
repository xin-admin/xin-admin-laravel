import { useModel } from '@umijs/max';
import { Badge, Tag } from 'antd';
import { useRequest } from 'ahooks';

interface DictItem {
  label: string;
  value: string;
  type?: 'badge' | 'tag';
  status?: 'success' | 'error' | 'default' | 'processing' | 'warning';
}


const showDom = (type: DictItem['type'], status: DictItem['status'], label: DictItem['label']) => {
  if(type === 'badge'){
    return <Badge status={status} text={label} />
  }else if(type === 'tag') {
    return <Tag color={status}>{label}</Tag>
  }else {
    return <>{label}</>
  }
}


const XinDict = (props: { value: any, dict: string }) => {
  const { value, dict } = props;
  const { getDictionaryData } = useModel('dictModel');

  const { data: dictData } = useRequest(async () => {
    return await getDictionaryData(dict);
  });

  const dictItem = dictData?.filter(d => d.value === value)[0];
  return (
    <>
      {dictItem && dictItem.type && dictItem.label ? showDom(dictItem.type, dictItem.status, dictItem.label) : value}
    </>
  );
}

export default XinDict
