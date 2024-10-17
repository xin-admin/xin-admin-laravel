import type { ProFormColumnsType } from '@ant-design/pro-components';
import { BetaSchemaForm } from '@ant-design/pro-components';
import { message } from 'antd';
import { AddGroup } from '@/services/admin/file/group';
import { FileAddOutlined } from '@ant-design/icons';


export default (props: { parentNode: GroupDataType[], getGroupList: () => Promise<any> }) => {
  const columns: ProFormColumnsType<GroupDataType>[] = [
    {
      title: '分组名称',
      dataIndex: 'name',
      initialValue: '必填',
      formItemProps: {
        rules: [
          {
            required: true,
            message: '此项为必填项',
          },
        ],
      },
    },
    {
      title: '父节点',
      dataIndex: 'parent_id',
      valueType: 'treeSelect',
      initialValue: '0',
      fieldProps: {
        options: props.parentNode,
        fieldNames: {
          label: 'name',
          value: 'group_id',
          children: 'children',
        },
      },
      formItemProps: {
        rules: [
          { required: true, message: '此项为必填项' },
        ],
      },
    },
    {
      title: '排序',
      dataIndex: 'sort',
      valueType: 'digit',
    },
  ];

  return (
    <>
      <BetaSchemaForm<GroupDataType>
        trigger={<FileAddOutlined />}
        layoutType='ModalForm'
        modalProps={{ width: 500 }}
        initialValues={{}}
        onFinish={async (values) => {
          await AddGroup(values);
          message.success('添加成功');
          await props.getGroupList();
          return true;
        }}
        columns={columns}
      />
    </>
  );
};
