import XinTable from '@/components/Xin/XinTable';
import { XinTableColumn } from '@/components/Xin/XinTable/typings';
import { DatePicker, Modal, Tabs, Tag } from 'antd';
import { useState, CSSProperties } from 'react';
import { ProDescriptions } from '@ant-design/pro-components';
import dayjs from 'dayjs';

interface IDataType {
  [key: string]: any;
}

export default () => {

  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modelData, setModelData] = useState<IDataType>();
  const [tabsKey, setTabsKey] = useState<string>('1');
  const [params, setParams] = useState({
    type: 'request',
    date: dayjs().format('YYYY-MM-DD'),
  })
  const methodValueEnum = {
    GET: { text: 'GET', status: 'Processing' },
    POST: { text: 'POST', status: 'Success' },
    DELETE: { text: 'DELETE', status: 'Error' },
    PUT: { text: 'PUT', status: 'Warning' },
  }
  const responseStatusRender = (status: any) => {
    if(status < 200) {
      return <Tag bordered={false}>{status}</Tag>
    } else if(status >= 200 && status < 300) {
      return <Tag bordered={false} color="success">{status}</Tag>
    } else if(status >= 300 && status < 400) {
      return <Tag bordered={false} color="processing">{status}</Tag>
    } else if(status >= 400 && status < 500) {
      return <Tag bordered={false} color="warning">{status}</Tag>
    } else {
      return <Tag bordered={false} color="error">{status}</Tag>
    }
  }
  const itemStyle: CSSProperties = {
    height: 300,
    width: '100%',
    overflow: 'auto',
    alignItems: 'initial'
  }
  const columns: XinTableColumn<IDataType>[] = [
    {
      title: '请求方法',
      align: 'center',
      dataIndex: 'method',
      key: 'method',
      valueEnum: methodValueEnum
    },
    {
      title: 'Path',
      dataIndex: 'uri',
      key: 'uri',
    },
    {
      title: 'IP地址',
      align: 'center',
      dataIndex: 'ip_address',
      key: 'ip_address',
    },
    {
      title: '主机名称',
      align: 'center',
      dataIndex: 'host_name',
      key: 'host_name',
    },
    {
      title: '请求状态',
      align: 'center',
      dataIndex: 'response_status',
      key: 'response_status',
      render: responseStatusRender
    },
    {
      title: '用时',
      align: 'center',
      dataIndex: 'duration',
      key: 'duration',
      render: (text) => <>{text} ms</>
    },
    {
      title: '请求时间',
      align: 'center',
      dataIndex: 'recorded_at',
      key: 'recorded_at',
    },
    {
      title: '操作',
      align: 'center',
      key: 'operate',
      render: (_, record) => (
        <a onClick={() => {
          setModelData(record);
          setIsModalOpen(true);
        }}>查看</a>
      )
    }
  ];

  return (
    <>
      <XinTable<IDataType>
        api={'/system/watcher/request'}
        rowKey={''}
        accessName={''}
        columns={columns}
        addShow={false}
        operateShow={false}
        tableProps={{
          headerTitle: '请求记录',
          toolbar: { settings: [] },
          toolBarRender: () => [
            <DatePicker
              onChange={(date, dateString) => setParams({ type: 'request', date: date.format('YYYY-MM-DD') })}
            />
          ],
          search: false,
          params: params,
          pagination: {
            showSizeChanger: true
          },
        }}
      />
      <Modal title="请求详情" width={800} open={isModalOpen} onCancel={() => setIsModalOpen(false)}>
        <ProDescriptions column={2} size={'default'} style={{paddingTop: 10}}>
          <ProDescriptions.Item span={2} valueType="text" ellipsis label="请求地址">
            { modelData?.uri }
          </ProDescriptions.Item>
          <ProDescriptions.Item span={2} valueType="text" ellipsis label="控制器方法">
            { modelData?.controller_action }
          </ProDescriptions.Item>
          <ProDescriptions.Item span={2} valueType="text" label="请求中间件">
            { modelData?.middleware.join("，") }
          </ProDescriptions.Item>
          <ProDescriptions.Item valueType="text" label="请求方法" valueEnum={methodValueEnum}>
            { modelData?.method }
          </ProDescriptions.Item>
          <ProDescriptions.Item valueType="text" label="请求状态">
            { responseStatusRender(modelData?.response_status) }
          </ProDescriptions.Item>
          <ProDescriptions.Item valueType="text" label="请求时间">
            { modelData?.recorded_at }
          </ProDescriptions.Item>
          <ProDescriptions.Item valueType="text" label="用时">
            { modelData?.duration } ms
          </ProDescriptions.Item>
          <ProDescriptions.Item valueType="text" label="内存使用情况">
            { modelData?.memory } MB
          </ProDescriptions.Item>
          <ProDescriptions.Item valueType="text" label="IP地址">
            { modelData?.ip_address }
          </ProDescriptions.Item>
          { modelData?.user && modelData?.user.id && (
            <>
              <ProDescriptions.Item valueType="text" label="用户ID">
                { modelData?.user.id }
              </ProDescriptions.Item>
              <ProDescriptions.Item valueType="text" label="用户名">
                { modelData?.user.name }
              </ProDescriptions.Item>
              <ProDescriptions.Item valueType="text" label="邮箱">
                { modelData?.user.email }
              </ProDescriptions.Item>
              <ProDescriptions.Item valueType="avatar" label="头像">
                { modelData?.user.avatar }
              </ProDescriptions.Item>
            </>
          )}
          <ProDescriptions.Item span={4} contentStyle={{ display: 'block' }}>
            <Tabs
              defaultActiveKey={tabsKey}
              items={[
                { label: '请求参数', key: '1', },
                { label: '请求头', key: '2', },
                { label: '响应参数', key: '3', },
                { label: '响应头', key: '4', },
                { label: 'Session', key: '5', }
              ]}
              onChange={(key) => setTabsKey(key)}
              tabBarStyle={{ margin: 0 }}
            />
          </ProDescriptions.Item>
          { tabsKey === '1' &&
            <ProDescriptions.Item span={2} valueType="jsonCode" contentStyle={itemStyle}>
              { JSON.stringify(modelData?.payload) }
            </ProDescriptions.Item>
          }
          { tabsKey === '2' &&
            <ProDescriptions.Item span={2} valueType="jsonCode" contentStyle={itemStyle}>
              { JSON.stringify(modelData?.headers) }
            </ProDescriptions.Item>
          }
          { tabsKey === '3' &&
            <ProDescriptions.Item span={2} valueType="jsonCode" contentStyle={itemStyle}>
              { JSON.stringify(modelData?.response) }
            </ProDescriptions.Item>
          }
          { tabsKey === '4' &&
            <ProDescriptions.Item span={2} valueType="jsonCode" contentStyle={itemStyle}>
              { JSON.stringify(modelData?.response_headers) }
            </ProDescriptions.Item>
          }
          { tabsKey === '5' &&
            <ProDescriptions.Item span={2} valueType="jsonCode" contentStyle={itemStyle}>
              { JSON.stringify(modelData?.session) }
            </ProDescriptions.Item>
          }
        </ProDescriptions>


      </Modal>
    </>
  );
}
