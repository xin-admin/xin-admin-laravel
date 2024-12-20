import type { ActionType, ProFormColumnsType, FormProps, ProFormInstance } from '@ant-design/pro-components';
import { BetaSchemaForm } from '@ant-design/pro-components';
import React, { useEffect, useRef, useState } from 'react';
import { addApi, editApi } from '@/services/common/table';
import { ColProps, message, type ModalProps, RowProps } from 'antd';

type FormRenderProps<FormData> = {
  // 表单 columns
  columns: ProFormColumnsType[];
  // 显示状态
  open: boolean;
  // API
  api: string;
  // 主键
  rowKey: string;
  // 表单 Ref
  formRef: React.MutableRefObject<ProFormInstance | undefined>;
  // 表格 Ref
  actionRef: React.MutableRefObject<ActionType | undefined>;
  // 初始数据
  initialValue?: FormData | undefined;
  // 标题
  title?: string;
  // Antd Form Props
  formProps?: {
    layout?: FormProps['layout']
    labelAlign?: FormProps['labelAlign']
    labelWrap?: FormProps['labelWrap']
    labelCol?: FormProps['labelCol']
    wrapperCol?: FormProps['wrapperCol']
  };
  // Antd Model Props
  modelProps?: ModalProps
  // Grid 布局
  grid?: boolean
  rowProps?: RowProps
  colProps?: ColProps
}

export default function FromRender<FormData extends Record<string, any>>(props: FormRenderProps<FormData>) {
  const {
    formRef,
    initialValue,
    grid = true,
    rowProps = {gutter: [16, 16]},
    colProps = {span: 12}
  } = props

  const [formType, setFormType] = useState<"edit" | "add">('add')

  useEffect(() => {
    if(initialValue) {
      formRef.current?.setFieldsValue(initialValue);
      setFormType('edit');
    }else {
      setFormType('add')
    }
  },[initialValue])

  /**
   * 提交表单
   * @param formData
   */
  const onFinish = async (formData: FormData) => {
    if(formType === 'edit' && initialValue) {
      await editApi(props.api, Object.assign(formData, {
        [props.rowKey]: initialValue[props.rowKey],
      }))
    }else {
      await addApi(props.api, formData)
    }
    message.success(formType === 'edit' ? '编辑成功' : '添加成功');
    props.actionRef.current?.reloadAndRest?.();
    return true
  }

  return (
    <>
      <BetaSchemaForm<FormData>
        title={props.title + " - " +  formType === 'add' ? '编辑' : '删除'}
        open={props.open}
        layoutType={'ModalForm'}
        onFinish={ onFinish }
        columns= { props.columns }
        {...props.formProps}
        modalProps={props.modelProps}
        grid={grid}
        formRef={formRef}
        rowProps={rowProps}
        colProps={colProps}
      />
    </>
  )
}
