import { useBoolean } from 'ahooks';
import React, { useRef, useState } from 'react';
import { ActionType, ProFormInstance } from '@ant-design/pro-components';
import FormRender from './FormRender';
import TableRender from './TableRender';
import { XinTablePropsType } from './typings';

export default function XinTable<T extends Record<string, any>>(props: XinTablePropsType<T>) {

  const { api, rowKey, columns, accessName, formProps, tableProps } = props;
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
        modelProps={{ onCancel: setOpen.setFalse }}
        formProps={props.formProps}
      />
      <TableRender<T>
        api={api}
        rowKey={rowKey}
        columns={columns}
        accessName={accessName}
        actionRef={actionRef}
        openForm={setOpen.setTrue}
        setFormInitValue={setFormInitValue}
        tableProps={tableProps}
      />
    </>
  );
}
