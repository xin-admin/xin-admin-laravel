import {
  BetaSchemaForm
} from '@ant-design/pro-components';
import { UpdateFromProps } from '../typings';
import { message } from "antd";
import { editApi } from "@/services/common/table";


function UpdateForm<TableData>(props: UpdateFromProps<TableData>) {
  const { columns, values, id, api, tableRef, handleUpdate } = props;

  /**
   * 更新节点
   * @param fields
   */
  const defaultUpdate = async (fields: TableData) => {
    if (handleUpdate) {
      return handleUpdate(Object.assign({ id }, fields)).finally(() => {
        tableRef.current?.reloadAndRest?.()
      })
    } else {
      const hide = message.loading('正在更新');
      return editApi(api, Object.assign({ id }, fields)).then(res => {
        if (res.success) {
          message.success('更新成功！');
          return true
        }
        return false
      }).finally(() => {
        tableRef.current?.reloadAndRest?.()
        hide()
      })
    }
  }

  return (
    <BetaSchemaForm<TableData>
      trigger={<a> 编辑</a>}
      title={'编辑'}
      layoutType={'ModalForm'}
      rowProps={{
        gutter: [16, 16],
      }}
      colProps={{
        span: 12,
      }}
      grid={true}
      initialValues={values}
      onFinish={defaultUpdate}
      columns={columns}
    />
  )
}
export default UpdateForm;
