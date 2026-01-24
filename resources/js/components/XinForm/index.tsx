import React, { useCallback, useImperativeHandle, useMemo, useState } from 'react';
import {
  Form,
  Button,
  Space,
  Row,
  Col,
  Modal,
  Drawer
} from 'antd';
import { useTranslation } from 'react-i18next';
import type { XinFormProps, XinFormRef, SubmitterButton } from './typings';
import type { FormItemProps } from 'antd';
import type { FormColumn } from '@/components/XinFormField/FieldRender/typings';
import FieldRender from '@/components/XinFormField/FieldRender';
import {pick} from "lodash";

/**
 * XinForm - JSON 配置动态表单组件
 */
function XinForm<T extends Record<string, any> = any>(props: XinFormProps<T>) {
  const {
    columns,
    layoutType = 'Form',
    grid = false,
    rowProps,
    colProps = { span: 12 },
    onFinish,
    formRef,
    modalProps,
    drawerProps,
    trigger,
    submitter,
    ...formProps
  } = props;

  const { t } = useTranslation();
  const [form] = Form.useForm<T>();
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);

  // 暴露表单方法
  useImperativeHandle(formRef, (): XinFormRef => ({
    ...form,
    open: handleOpen,
    close: handleClose,
    isOpen: () => open,
    setLoading: (loading: boolean) => setLoading(loading),
  }));

  // 表单提交处理
  const handleFinish = useCallback(async (values: T) => {
    if (!onFinish) return;
    try {
      setLoading(true);
      const result = await onFinish(values);
      if (result !== false && (layoutType === 'ModalForm' || layoutType === 'DrawerForm')) {
        setOpen(false);
      }
    } finally {
      setLoading(false);
    }
  }, [onFinish, layoutType]);

  // 打开弹窗/抽屉
  const handleOpen =() => setOpen(true);

  // 关闭弹窗/抽屉
  const handleClose = () => setOpen(false);

  // 渲染表单项
  const renderFormItem = useCallback((column: FormColumn<T>, index: number): React.ReactNode => {
    const {
      dataIndex,
      valueType,
      title = '',
      fieldProps = {},
      dependency,
      fieldRender,
      colProps: columnColProps = {}
    } = column;

    // Form.Item 允许的属性列表
    const formItemPropKeys = [
      'colon', 'extra', 'getValueFromEvent', 'help', 'hidden', 'htmlFor',
      'initialValue',   'name', 'normalize',
      'noStyle', 'preserve', 'tooltip', 'trigger',
      // 验证相关
      'required', 'rules', 'validateFirst', 'validateDebounce', 'validateStatus', 'hasFeedback',
      'validateTrigger', 'valuePropName', 'messageVariables',
      // 布局
      'wrapperCol', 'layout', 'label', 'labelAlign', 'labelCol'
    ];

    const formItemProps = pick(column, formItemPropKeys);

    const key = String(dataIndex) || `form-item-${index}`;

    let formItemContent;
    if (dependency) {
      // 有依赖时使用 Form.Item 的 shouldUpdate
      formItemContent = (
        <Form.Item noStyle shouldUpdate={(prevValues, curValues) => {
          return dependency.dependencies.some(
            (dep) => prevValues[dep as string] !== curValues[dep as string]
          );
        }}>
          {({getFieldsValue}) => {
            const values = getFieldsValue() as T;
            // 判断是否隐藏
            const isHidden = dependency.visible ? !dependency.visible(values) : false;
            if (isHidden) return null;

            // 判断是否禁用
            const isDisabled = dependency.disabled ? dependency.disabled(values) : false;
            // 动态 fieldProps
            const dynamicFieldProps = dependency.fieldProps ? dependency.fieldProps(values) : {};

            const mergedFieldProps = {
              ...fieldProps,
              ...dynamicFieldProps,
              disabled: isDisabled || fieldProps?.disabled,
            }

            const defaultFieldRender = (
              <FieldRender
                valueType={valueType}
                placeholder={title}
                {...mergedFieldProps}
              />
            );

            return (
              <Form.Item
                key={key}
                name={key}
                label={column.title || column.label}
                {...formItemProps as FormItemProps}
              >
                {fieldRender ? fieldRender(form) : defaultFieldRender}
              </Form.Item>
            );
          }}
        </Form.Item>
      );
    } else {

      const defaultFieldRender = (
        <FieldRender
          valueType={valueType}
          placeholder={title}
          {...fieldProps}
        />
      );
      // 普通表单项
      formItemContent = (
        <Form.Item
          key={key}
          name={key}
          label={column.title || column.label}
          {...formItemProps as FormItemProps}
        >
          {fieldRender ? fieldRender(form) : defaultFieldRender}
        </Form.Item>
      );
    }
    return grid ? <Col {...Object.assign(colProps, columnColProps)} key={key}>{formItemContent}</Col> : formItemContent;
  }, [grid, form]);

  // 渲染提交按钮
  const renderSubmitter = useMemo(() => {
    if (submitter?.render === false) return null;

    const submitButton = (
      <Button
        type="primary"
        loading={loading}
        onClick={() => form.submit()}
        {...submitter?.submitButtonProps}
      >
        {submitter?.submitText || t('xinForm.submit')}
      </Button>
    );

    const closeButton = (
      <Button
        loading={loading}
        onClick={handleClose}
        {...submitter?.closeButtonProps}
      >
        {submitter?.closeText || t('xinForm.cancel')}
      </Button>
    );

    const resetButton = (
      <Button
        loading={loading}
        onClick={() => form.resetFields()}
        {...submitter?.resetButtonProps}
      >
        {submitter?.resetText || t('xinForm.reset')}
      </Button>
    );

    const buttons: SubmitterButton = {
      submit: submitButton,
      close: closeButton,
      reset: resetButton,
    };

    if (typeof submitter?.render === 'function') {
      return submitter.render(buttons, formRef);
    }

    if(layoutType === 'Form') {
      return (
        <Form.Item>
          <Space>
            {buttons.reset}
            {buttons.submit}
          </Space>
        </Form.Item>
      );
    }else {
      return (
        <Space>
          {buttons.reset}
          {buttons.submit}
          {buttons.close}
        </Space>
      );
    }
  }, [loading, form, submitter, t]);

  // 表单内容
  const formContent = useMemo(() => (
    <Form
      {...formProps}
      form={form}
      onFinish={handleFinish}
    >
      {grid ? (
        <Row {...rowProps}>
          {columns.map((column, index) => renderFormItem(column, index))}
        </Row>
      ) : (
        columns.map((column, index) => renderFormItem(column, index))
      )}
      {(layoutType === 'Form') && renderSubmitter}
    </Form>
  ), [ form, handleFinish, props, grid, rowProps, columns, renderFormItem, layoutType, renderSubmitter ]);

  // 触发器
  const triggerElement = useMemo(() => {
    if (!trigger) return null;
    return React.cloneElement(trigger as React.ReactElement<{ onClick?: () => void }>, {
      onClick: handleOpen,
    });
  }, [trigger, handleOpen]);

  // 根据 layoutType 渲染
  if (layoutType === 'ModalForm') {
    return (
      <>
        {triggerElement}
        <Modal
          open={open}
          onCancel={handleClose}
          footer={renderSubmitter}
          {...modalProps}
        >
          {formContent}
        </Modal>
      </>
    );
  }

  if (layoutType === 'DrawerForm') {
    return (
      <>
        {triggerElement}
        <Drawer
          open={open}
          onClose={handleClose}
          footer={renderSubmitter}
          {...drawerProps}
        >
          {formContent}
        </Drawer>
      </>
    );
  }

  return formContent;
}

export default XinForm;

export type { XinFormProps, XinFormRef };
