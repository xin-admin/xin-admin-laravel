import React, {useCallback, useMemo, useState} from 'react';
import {
  Form,
  Button,
  Space, Row, Col,
} from 'antd';
import { useTranslation } from 'react-i18next';
import type { SearchFormProps, SubmitterButton } from './typings';
import type { FormItemProps } from 'antd';
import type { FormColumn } from "@/components/XinFormField/FieldRender";
import FieldRender from '@/components/XinFormField/FieldRender';
import { pick } from 'lodash';
import {CaretDownOutlined, CaretUpOutlined} from "@ant-design/icons";

/**
 * SearchForm - JSON 配置动态搜索表单组件
 */
function SearchForm<T extends Record<string, any> = any>(props: SearchFormProps<T>) {
  const {
    columns,
    handleSearch,
    submitter,
    form: formRef,
    grid = true,
    rowProps = {
      gutter: [20, 20],
      wrap: true
    },
    colProps = {
      xs: 24,
      sm: 12,
      md: 8,
      lg: 8,
      xl: 6,
    },
    ...formProps
  } = props;

  const { t } = useTranslation();
  const [form] = Form.useForm<T>(formRef);
  const [collapse, setCollapse] = useState(false);
  // 打开弹窗/抽屉
  const onCollapse = () => setCollapse(!collapse);

  // 渲染表单项
  const renderFormItem = useCallback((column: FormColumn<T>, index: number): React.ReactNode => {
    const {
      dataIndex, 
      valueType,
      title = '', 
      fieldProps = {},
      fieldRender
    } = column;

    // Form.Item 允许的属性列表
    const formItemPropKeys = [
      'colon', 'extra', 'getValueFromEvent', 'help', 'htmlFor',
      'initialValue', 'labelAlign', 'labelCol', 'name', 'normalize',
      'noStyle', 'tooltip', 'wrapperCol', 'layout'
    ];
    const formItemProps = pick(column, formItemPropKeys);

    const key = String(dataIndex) || `form-item-${index}`;

    const defaultFieldRender = (
      <FieldRender 
        valueType={valueType}
        placeholder={title}
        {...fieldProps}
      />
    );

    const formItemContent =  (
      <Form.Item
        style={{marginBottom: 0}}
        key={key}
        name={key}
        label={column.title}
        {...formItemProps as FormItemProps}
        required={false}
      >
        {fieldRender ? fieldRender(form) : defaultFieldRender}
      </Form.Item>
    );

    return grid ? <Col {...Object.assign(colProps, column.colProps)} key={key}>{formItemContent}</Col> : formItemContent;
  }, [form, grid, colProps, pick]);

  // 渲染提交按钮
  const renderSubmitter = useMemo(() => {
    if (submitter?.render === false) return null;

    const submitButton = (
      <Button
        type="primary"
        onClick={() => form.submit()}
        {...submitter?.submitButtonProps}
      >
        {submitter?.submitText || t('xinTableV2.search.search')}
      </Button>
    );

    const resetButton = (
      <Button
        onClick={() => form.resetFields()}
        {...submitter?.resetButtonProps}
      >
        {submitter?.resetText || t('xinTableV2.search.reset')}
      </Button>
    );

    const collapseButtonRender = (collapsed: boolean) => {
      if (collapsed) {
        return (
          <Space>
            { t('xinTableV2.search.collapse') }
            <CaretUpOutlined />
          </Space>
        )
      } else {
        return (
          <Space>
            { t('xinTableV2.search.expand') }
            <CaretDownOutlined />
          </Space>
        )
      }
    };

    const collapseButton = (
      <a onClick={onCollapse}>
        { submitter?.collapseRender ? submitter.collapseRender(collapse) : collapseButtonRender(collapse) }
      </a>
    );

    const buttons: SubmitterButton = {
      search: submitButton,
      reset: resetButton,
      collapse: collapseButton
    };

    if (typeof submitter?.render === 'function') {
      return submitter.render(buttons);
    }

    return (
      <Form.Item style={{marginBottom: 0}}>
        <Space size={16}>
          {buttons.reset}
          {buttons.search}
          {buttons.collapse}
        </Space>
      </Form.Item>
    );
  }, [form, submitter, t, onCollapse]);

  // 表单内容
  const formBodyRender = () => {
    if(collapse) {
      return columns.map((column, index) => renderFormItem(column, index));
    } else {
      return columns.slice(0, 3).map((column, index) => renderFormItem(column, index));
    }
  };

  // 表单内容
  return (
    <Form
      {...formProps}
      form={form}
      onFinish={handleSearch}
      style={{ width: '100%' }}
    >
      {grid ? (
        <Row {...rowProps}>
          {...formBodyRender()}
          <Col {...colProps}>{renderSubmitter}</Col>
        </Row>
      ) : (
        <>
          {...formBodyRender()}
          {renderSubmitter}
        </>
      )}
    </Form>
  )
}

export default SearchForm;
export type { SearchFormProps };
