import { ProCard } from '@ant-design/pro-components';
import React, { useEffect, useState } from 'react';
import { GetProps, message, Space, Tree } from 'antd';
import { DeleteOutlined } from '@ant-design/icons';
import IconFont from '@/components/IconFont';
import EditGroup from './EditGroup';
import AddGroup from './AddGroup';
import { DeleteGroup, GroupList } from '@/services/admin/file/group';
type DirectoryTreeProps = GetProps<typeof Tree.DirectoryTree>;
const { DirectoryTree } = Tree;

const FileIcon = <IconFont type={'icon-wenjianjia'} className={'icon-wenjianjia'} />;
export default (props: {selectGroup: GroupDataType,setSelectGroup: React.Dispatch<React.SetStateAction<GroupDataType>>}) => {
  const {selectGroup,setSelectGroup} = props

  const [groupData, setGroupData] = useState<GroupDataType[]>([
    {
      name: 'root',
      group_id: 0,
      selectable: false,
    },
  ]);
  const getGroupList = async () => {
    let res = await GroupList();
    setGroupData([
      {
        name: 'root',
        group_id: 0,
        children: res.data,
      },
    ]);
  };
  useEffect(() => {
    getGroupList().then(() => {
    });
  }, []);

  const onSelect: DirectoryTreeProps['onSelect'] = (keys, { selectedNodes }) => {
    // @ts-ignore
    let data: GroupData = selectedNodes[0];
    setSelectGroup(data);
  };

  const  deleteGroup = async () => {
    let res = await DeleteGroup({group_id: selectGroup.group_id})
    if (res.success) {
      message.success(res.msg);
      await getGroupList()
      setSelectGroup({
        name: 'root',
        group_id: 0,
        parent_id: 0,
        sort: 0,
      })
    }else {
      message.warning(res.msg);
    }
  }

  const fileGroupActions = [
    <>{FileIcon}&nbsp;{selectGroup.name}</>,
    <Space key={'option'}>
      {/* eslint-disable-next-line react/jsx-no-undef */}
      {selectGroup.group_id === 0 ? '' : <EditGroup getGroupList={getGroupList} parentNode={groupData} defaultData={selectGroup}></EditGroup>}
      {selectGroup.group_id === 0 ? '' : <DeleteOutlined onClick={deleteGroup} />}
      <AddGroup getGroupList={getGroupList} parentNode={groupData}></AddGroup>
    </Space>,
  ];

  return (
    <ProCard
      actions={fileGroupActions}
      bordered
      headerBordered
      title={'文件分组'}
    >
      <DirectoryTree
        showLine
        fieldNames={{ title: 'name', key: 'group_id', children: 'children' }}
        icon={FileIcon}
        defaultExpandAll
        autoExpandParent
        onSelect={onSelect}
        treeData={groupData}
      />
    </ProCard>
  )
}
