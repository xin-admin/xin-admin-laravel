import { Drawer, message } from 'antd';
import XinTable from '@/components/XinTable'
import { ProFormColumnsAndProColumns } from '@/components/XinTable/typings';
import { addApi, listApi } from "@/services/common/table";
import { ProTableProps } from "@ant-design/pro-components";
import { useModel } from '@umijs/max';

const api = '/system/dictItem';

interface Data {
  id?: number
  name?: string
  value?: string
  create_time?: string
  weight?: string
  status?: string
  update_time?: string
}

const columns: ProFormColumnsAndProColumns<Data>[] = [
  {
    title: 'ID',
    dataIndex: 'id',
    hideInForm: true,
    hideInTable: true
  },
  {
    title: '名称',
    dataIndex: 'label',
    valueType: 'text',
  },
  {
    title: '数据值',
    dataIndex: 'value',
    valueType: 'text'
  },
  {
    title: '状态类型',
    dataIndex: 'status',
    valueType: 'text',
    valueEnum: {
      success: { text: 'success', status: 'Success' },
      error: { text: 'error', status: 'Error' },
      default: { text: 'default', status: 'Default' },
      processing: { text: 'processing', status: 'Processing' },
      warning: { text: 'warning', status: 'Warning' },
    },
    initialValue: 'default'
  },
  {
    title: '是否启用',
    dataIndex: 'switch',
    valueType: 'switch',
    initialValue: true
  },
  {
    title: '创建时间',
    dataIndex: 'create_time',
    valueType: 'date',
    hideInForm: true,
    hideInTable: true
  },
  {
    title: '修改时间',
    dataIndex: 'update_time',
    valueType: 'date',
    hideInForm: true,
    hideInTable: true
  },
];
const App: React.FC<{ open: boolean; onClose: () => void; dictData: { [key: string]: any } }> = (props) => {
  const { open, onClose, dictData } = props;
  const { refreshDict } = useModel('dictModel')

  const handleAdd = async (formData: Data) => {
    const hide = message.loading('正在添加');
    return addApi(api + '/add', Object.assign({ dict_id: dictData.id }, formData)).then(res => {
      if (res.success) {
        message.success('添加成功');
        refreshDict();
        return true
      }
      return false
    }).finally(() => hide());
  };

  const request: ProTableProps<Data, any>['request'] = async (params, sorter, filter) => {
    const { data, success } = await listApi(api + '/list', {
      ...params,
      sorter,
      filter,
      dict_id: dictData.id
    });
    return {
      data: data?.data || [],
      success,
      total: data?.total
    };
  }

  return (
    <Drawer
      title={dictData.name}
      width={720}
      onClose={onClose}
      open={open}
      bodyStyle={{ paddingBottom: 80 }}
    >
      <XinTable<Data>
        search={false}
        headerTitle={dictData.name}
        key={dictData.id}
        tableApi={api}
        columns={columns}
        handleAdd={handleAdd}
        rowSelectionShow={false}
        accessName={'system.dict.item'}
        request={request}
      />
    </Drawer>
  );
};

export default App;
