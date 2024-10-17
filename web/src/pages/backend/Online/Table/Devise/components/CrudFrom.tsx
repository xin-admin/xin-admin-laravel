import type { ProFormColumnsType, ProFormInstance } from '@ant-design/pro-components';
import { BetaSchemaForm } from '@ant-design/pro-components';
import { useContext, useEffect } from 'react';
import { useRef } from 'react';
import TableConfigContext from './TableConfigContext';

const columns: ProFormColumnsType<OnlineType.CrudConfig>[] = [
  {
    title: '数据表名称',
    dataIndex: 'sqlTableName',
    valueType: 'text',
    fieldProps: {
      placeholder: '请输入数据表名称',
    },
    tooltip: '必填,不带数据表前缀，生成自动添加'
  },
  {
    title: '数据库备注',
    dataIndex: 'sqlTableRemark',
    valueType: 'text',
  },
  {
    title: '生成文件名',
    dataIndex: 'name',
    valueType: 'text',
    tooltip: '请使用大驼峰命名'
  },
  {
    title: '控制器目录',
    dataIndex: 'controllerPath',
    valueType: 'text',
    fieldProps: {
      placeholder: '请输入路径',
      addonBefore: 'app/admin/controller/',
    },
    tooltip: '将生成（app/admin/controller/ + 输入路径 + 文件名 + Controller.php）文件， 如果在控制器根目录则省略不填'
  },
  {
    title: '模型目录',
    dataIndex: 'modelPath',
    valueType: 'text',
    fieldProps: {
      placeholder: '请输入模型路径',
      addonBefore: 'app/admin/model/',
    },
    tooltip: '将生成（app/admin/model/ + 输入路径 + 文件名 + Model.php）文件， 如果在模型根目录则省略不填'
  },
  {
    title: '验证器目录',
    dataIndex: 'validatePath',
    valueType: 'text',
    fieldProps: {
      placeholder: '请输入验证器路径',
      addonBefore: 'app/admin/validate/',
    },
    tooltip: '将生成（app/admin/validate/ + 输入路径 + 文件名 + .php）文件， 如果在验证器根目录则省略不填'
  },
  {
    title: '前端页面目录',
    dataIndex: 'pagePath',
    valueType: 'text',
    fieldProps: {
      placeholder: '请输入前端页面目录',
      addonBefore: 'src/pages/backend/',
    },
    tooltip: '将生成（web/admin/src/pages/backend/ + 输入路径 + 文件夹（文件名） + .tsx）文件， 如果在前端页面根目录则省略不填，如果自行迁移前端项目路径请在 .env 文件中修改路径'
  },
  {
    valueType: 'switch',
    title: '开启软删除',
    dataIndex: 'autoDeletetime',
    fieldProps: {
      style: {
        width: '200px',
      },
    },
  },
];

export default () => {

  const { tableConfig, setTableConfig } = useContext(TableConfigContext);

  const formRef = useRef<ProFormInstance>()
  useEffect(() => {
    if (tableConfig) {
      formRef.current?.setFieldsValue(tableConfig.crudConfig)
    }
  }, [tableConfig])

  return (
    <>
      <BetaSchemaForm<OnlineType.CrudConfig>
        onValuesChange={() => setTableConfig({
          ...tableConfig,
          crudConfig: formRef.current?.getFieldsValue()
        })}
        layoutType={'Form'}
        layout={'inline'}
        colProps={{ span: 8 }}
        labelCol={{ span: 7 }}
        grid={true}
        initialValues={{
          name: 'TableName',
          controllerPath: '',
          modelPath: '',
          validatePath: '',
          pagePath: '',
        }}
        formRef={formRef}
        columns={columns}
        submitter={{ render: () => <></> }}
      />
    </>
  );
};
