import { BetaSchemaForm } from '@ant-design/pro-components';
import React, { useEffect, useState } from 'react';
import { addApi, editApi } from '@/services/common/table';
import { message } from 'antd';
import { FormRenderProps } from './typings';

export default function FromRender<FormData extends Record<string, any>>(props: FormRenderProps<FormData>) {
  const {
    formRef,
    initialValue,
    grid = true,
    afterFinish,
    rowProps = { gutter: [16, 16] },
    colProps = { span: 12 },
  } = props;

  const [formType, setFormType] = useState<'edit' | 'add'>('add');

  useEffect(() => {
    if (initialValue) {
      formRef.current?.setFieldsValue(initialValue);
      setFormType('edit');
    } else {
      setFormType('add');
    }
  }, [initialValue]);

  // 提交表单
  const onFinish = async (formData: FormData) => {
    if (formType === 'edit' && initialValue) {
      await editApi(props.api, Object.assign(formData, {
        [props.rowKey]: initialValue[props.rowKey],
      }));
    } else {
      await addApi(props.api, formData);
    }
    message.success(formType === 'edit' ? '编辑成功' : '添加成功');
    afterFinish?.(formData, initialValue)
    return true;
  };

  return (
    <>
      <BetaSchemaForm<FormData>
        title={props.title + ' - ' + formType === 'add' ? '编辑' : '删除'}
        open={props.open}
        layoutType={'ModalForm'}
        onFinish={onFinish}
        columns={props.columns}
        {...props.formProps}
        modalProps={props.modelProps}
        grid={grid}
        formRef={formRef}
        rowProps={rowProps}
        colProps={colProps}
      />
    </>
  );
}
