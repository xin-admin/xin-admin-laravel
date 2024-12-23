import { BetaSchemaForm } from '@ant-design/pro-components';
import React, { useEffect } from 'react';
import { addApi, editApi } from '@/services/common/table';
import { message } from 'antd';
import { FormRenderProps } from './typings';

export default function FromRender<FormData extends Record<string, any>>(props: FormRenderProps<FormData>) {
  const {
    formRef,
    initialValue,
    afterFinish,
    formProps = {
      grid: true,
      rowProps: { gutter: 20 },
      colProps: { span: 12 },
    },
  } = props;

  useEffect(() => {
    if (initialValue) {
      formRef.current?.setFieldsValue(initialValue);
    } else {
      formRef.current?.resetFields();
    }
  }, [initialValue]);

  // 提交表单
  const onFinish = async (formData: FormData) => {
    if (initialValue) {
      await editApi(props.api, Object.assign(formData, {
        [props.rowKey]: initialValue[props.rowKey],
      }));
    } else {
      await addApi(props.api, formData);
    }
    message.success('提交成功');
    afterFinish?.(formData, initialValue);
    return true;
  };

  return (
    <>
      <BetaSchemaForm<FormData>
        open={props.open}
        layoutType={'ModalForm'}
        onFinish={onFinish}
        columns={props.columns}
        modalProps={props.modelProps}
        formRef={formRef}
        {...formProps}
      />
    </>
  );
}
