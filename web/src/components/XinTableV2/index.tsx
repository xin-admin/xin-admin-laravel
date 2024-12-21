import { useBoolean } from 'ahooks';
import React, { useRef, useState } from 'react';
import { ActionType, ProColumns, ProFormColumnsType, ProFormInstance } from '@ant-design/pro-components';
import FormRender from './FormRender';
import TableRender from './TableRender';
import { XinTableColumnType } from './typings';

export default function XinTable<T extends Record<string, any>>(props: {
  // 表单 columns
  columns: XinTableColumnType<T>[];
  // API
  api: string;
  // 主键
  rowKey: string;
  // 权限
  accessName: string;
  // 标题
  title: React.ReactNode;
}) {

  const {api, rowKey, columns, accessName, title} = props
  // 表格 Ref
  const actionRef = useRef<ActionType>();
  // 表单 Ref
  const formRef = useRef<ProFormInstance>();
  // 表单开启状态
  const [open, setOpen] = useBoolean(false);
  // 表单初始数据
  const [formInitValue, setFormInitValue] = useState<T | false>(false);

  return (
    <>
      <FormRender<T>
        columns={columns}
        open={open}
        api={api}
        rowKey={rowKey}
        formRef={formRef}
        initialValue={formInitValue}
        afterFinish={() => {
          actionRef.current?.reloadAndRest?.();
        }}
        modelProps={{
          onCancel: setOpen.setFalse,
        }}
      />
      <TableRender<T>
        api={api}
        title={title}
        rowKey={rowKey}
        columns={columns}
        accessName={accessName}
        actionRef={actionRef}
        openForm={setOpen.setTrue}
        setFormInitValue={setFormInitValue}
      />
    </>
  )
}
