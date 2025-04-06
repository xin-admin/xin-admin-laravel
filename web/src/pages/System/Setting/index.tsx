import React from 'react';
import { Alert, Col, Row } from 'antd';
import SettingRender from './components/Setting';
import XinTable from '@/components/Xin/XinTable';

export default () => {

  return (
    <Row gutter={[20, 20]} wrap={false}>
      <Col flex="680px">
        <SettingRender></SettingRender>
      </Col>
      <Col flex="auto">
        <Alert
          message="系统设置"
          description="系统设置是 XinAdmin 为开发者提供的数据快捷配置解决方案，通过对业务中需要动态配置的值进行可视化的配置，并且提供了助手函数，方便后端开发者快速的获取和使用系统设置，目前已经支持输入框、文本域、数字输入框、单选框、多选框、开关等，后续会陆续添加对其它表单组件的支持。"
          type="info"
          closable
          style={{marginBottom: 20}}
        />
        <XinTable
          api={'/system/setting/group'}
          rowKey={'id'}
          accessName={'system.setting.group'}
          columns={[
            { title: '分组ID', dataIndex: 'id', hideInForm: true, sorter: true},
            { title: 'KEY', dataIndex: 'key', valueType: 'text' },
            { title: '分组标题', dataIndex: 'title', valueType: 'text' },
            { title: '分组描述', dataIndex: 'remark', valueType: 'textarea' },
            { title: '创建时间', dataIndex: 'created_at', hideInForm: true, valueType: 'fromNow' },
            { title: '更新时间', dataIndex: 'updated_at', hideInForm: true, valueType: 'fromNow' },
          ]}
          tableProps={{
            search: false,
            headerTitle: '设置分组',
            toolbar: { settings: [] },
            pagination: { pageSize: 10 },
            cardProps: { bordered: true }
          }}
        />
      </Col>
    </Row>
  );
}
