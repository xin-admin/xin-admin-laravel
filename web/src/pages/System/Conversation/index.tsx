import XinTable from '@/components/XinTable';
import { Avatar, Col, Row, Space, Tag } from 'antd';
import { IAiConversationGroup } from '@/domain/iAiConversationGroup';

export default () => {

  return (
    <Row gutter={20}>
      <Col span={16}>
        <XinTable<IAiConversationGroup>
          api={'/system/conversation/group'}
          rowKey={'id'}
          accessName={'system.conversation.group'}
          tableProps={{
            headerTitle: "会话组",
            search: false,
            toolbar: { settings: [] },
          }}
          columns={[
            { title: '', dataIndex: 'id', sorter: true, align: 'center' },
            { title: '会话ID', dataIndex: 'uuid', align: 'center' },
            { title: '会话名称', dataIndex: 'name', align: 'center' },
            { title: '会话模型', dataIndex: 'model', align: 'center' },
            { title: '用户', dataIndex: 'user', align: 'center', render: (text, record) => (
              <Space>
                <Avatar src={record.user?.avatar_url}/>
                { record.user?.nickname }
              </Space>
            )},
            { title: '创建时间', dataIndex: 'created_at', align: 'center', valueType: 'fromNow' },
          ]}
        />
      </Col>
      <Col span={8}>
        <XinTable<IAiConversationGroup>
          api={'/system/conversation'}
          rowKey={'id'}
          accessName={'system.conversation'}
          tableProps={{
            headerTitle: "会话内容",
            search: false,
            // params: { dict_id: selectedRows.id },
            toolbar: { settings: [] },
            pagination: { pageSize: 10 }
          }}
          columns={[
            { title: '角色', dataIndex: 'role', render: (text) => <Tag>{text}</Tag> },
            { title: '内容', dataIndex: 'message', align: 'center', ellipsis: true },
            { title: '创建时间', dataIndex: 'created_at', align: 'center', valueType: 'fromNow' },
          ]}
        />
      </Col>
    </Row>
  )
}
