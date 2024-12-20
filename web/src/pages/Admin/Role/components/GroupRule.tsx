import { Button, Drawer, message, Space, Tree, TreeProps } from 'antd';
import React, { useEffect, useState } from 'react';
import * as Api from '@/services/auth';
import { useBoolean } from 'ahooks';
import {GroupListType} from '../index'

export default (props: { record: GroupListType, treeData: any }) => {

  const { record, treeData } = props;
  const [open, setOpen] = useBoolean(false);
  const [checkedKeys, setCheckedKeys] = useState<React.Key[]>([]);
  const [halfCheckedKeys, setHalfCheckedKeys] = useState<React.Key[]>([]);

  useEffect(() => {
    setCheckedKeys(record.rules);
  }, [record]);

  const onCheck: TreeProps['onCheck'] = (checked, e) => {
    if(Array.isArray(checked)){
      setCheckedKeys(checked);
    }else {
      setCheckedKeys(checked.checked);
    }
    setHalfCheckedKeys(e.halfCheckedKeys ? e.halfCheckedKeys : []);
  };

  /**
   * 保存权限规则
   */
  const onSave = async () => {
    let data = {
      'id': record.id,
      'rule_ids': [...checkedKeys, ...halfCheckedKeys],
    }
    await Api.setGroupRule(data)
    message.success('保存成功');
  }

  return (
    <>
      <a onClick={() => setOpen.setTrue()}>编辑权限</a>

    </>
  );
};
