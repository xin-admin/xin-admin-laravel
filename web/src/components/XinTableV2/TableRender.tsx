import { deleteApi, listApi } from '@/services/common/table';
import { ProColumns, ProTable } from '@ant-design/pro-components';
import React from 'react';
import { Button, Divider, message, Popconfirm, Space } from 'antd';
import ButtonAccess from '@/components/ButtonAccess';
import { TableRenderProps } from './typings';

export default function TableRender<T extends Record<string, any>>(props: TableRenderProps<T>) {
  // 新增按钮点击事件
  const addButtonClick = () => {
    props.setFormInitValue(false)
    props.openForm()
  }
  // 编辑按钮点击事件
  const editButtonClick = (record: T) => {
    props.setFormInitValue(record)
    props.openForm()
  }
  // 删除按钮点击事件
  const deleteButtonClick = async (record: T) => {
    await deleteApi(props.api, { ids: record[props.rowKey] })
    message.success('删除成功！')
  }

  // 表格操作列
  const operateRender = (): ProColumns<T>[]  => {
    if(props.operateShow === false) return [];
    const operate: ProColumns<T> = {
      title: '操作',
      dataIndex: 'option',
      valueType: 'option',
      hideInForm: true,
      hideInSearch: true,
      hideInDescriptions: true,
      render: (_, record) => (
        <Space split={ <Divider type="vertical" /> } size={0}>
          { props.addShow !== false &&
            <ButtonAccess auth={ props.accessName + '.edit' }>
              <Button children={'编辑'} type={'link'} onClick={() => editButtonClick(record)} />
            </ButtonAccess>
          }
          { props.editShow !== false &&
            <ButtonAccess auth={ props.accessName + '.delete'}>
              <Popconfirm
                okText="确认"
                cancelText="取消"
                title="Delete the task"
                description="你确定要删除这条数据吗？"
                onConfirm={() => deleteButtonClick(record)}
              >
                <Button type={'link'}>删除</Button>
              </Popconfirm>
            </ButtonAccess>
          }
          { props.operateRender }
        </Space>
      ),
    }
    return [operate]
  }

  // 操作栏渲染
  const toolBarRender = ():  React.ReactNode[] => {
    let defaultToolbar: React.ReactNode[] = [
      <ButtonAccess auth={ props.accessName + '.add'}>
        <Button children={'新增'} type={'link'} onClick={addButtonClick} />
      </ButtonAccess>
    ]
    if(props.toolBarRender) {
      defaultToolbar = [...defaultToolbar, ...props.toolBarRender]
    }
    return defaultToolbar
  }

  return (
    <ProTable<T>
      columns={[...props.columns, ...operateRender()]}
      actionRef={props.actionRef}
      headerTitle={props.title}
      rowKey={props.rowKey}
      toolBarRender={toolBarRender}
      request={async (params, sorter, filter) => {
        const { data, success } = await listApi(props.api, { ...params, sorter, filter });
        return { ...data, success, }
      }}
      {...props.tableProps}
    />
  )
}
