import { Button, Drawer, message, Space, Tree, TreeProps } from 'antd';
import React, { useEffect, useState } from 'react';
import * as Api from '@/services/admin/auth';
import { useBoolean } from 'ahooks';

interface GroupListType {
  id: number;
  name: string;
  pid: string;
  rules: number[];
  create_time: string;
  update_time: string;
}

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
      <Drawer
        title={record.name}
        width={520}
        onClose={() => setOpen.setFalse()}
        open={open}
        styles={{ body: { paddingBottom: 80 } }}
        extra={
          <Space>
            <Button onClick={onSave} type='primary'>
              保存
            </Button>
          </Space>
        }
      >
        {
          treeData?.length > 1 && <Tree
            checkable
            defaultExpandAll
            blockNode={false}
            treeData={treeData}
            fieldNames={{
              title: 'name',
              key: 'id',
              children: 'children',
            }}
            showLine
            onCheck={onCheck}
            checkedKeys={checkedKeys}
          />
        }
      </Drawer>
    </>
  );
};
