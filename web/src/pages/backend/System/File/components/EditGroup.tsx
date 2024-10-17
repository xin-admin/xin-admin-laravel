import type { ProFormColumnsType, ProFormInstance } from '@ant-design/pro-components';
import { BetaSchemaForm } from '@ant-design/pro-components';
import { message } from 'antd';
import { EditGroup } from '@/services/admin/file/group';
import { EditOutlined } from '@ant-design/icons';
import React, { useEffect, useRef } from 'react';

interface formData {
  name: string;
  parent_id?: number;
  sort?: number;
}

export default (props: { parentNode: GroupDataType[], defaultData: GroupDataType, getGroupList: () =>  Promise<void> }) => {
  const { parentNode, defaultData, getGroupList } = props;
  const columns: ProFormColumnsType<formData>[] = [
    {
      title: '分组名称',
      dataIndex: 'name',
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
      fieldProps: {
        options: parentNode,
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
  const form = useRef<ProFormInstance>();

  useEffect(() => {
    form.current?.setFieldsValue(defaultData)
  },[defaultData])

  return (
    <>
      <BetaSchemaForm<formData>
        trigger={<EditOutlined />}
        layoutType='ModalForm'
        modalProps={{width: 500}}
        initialValues={defaultData}
        formRef={form}
        onFinish={async (values) => {
          let data = { group_id: defaultData.group_id, ...values };
          await EditGroup(data);
          message.success('编辑成功');
          await getGroupList()
          return true;
        }}
        columns={columns}
      />
    </>
  );
};
