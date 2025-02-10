import { BetaSchemaForm, ProFormColumnsType, ProFormInstance, useDebounceFn } from '@ant-design/pro-components';
import React, { useEffect, useRef } from 'react';
import { defaultCRUDConfig } from '@/pages/Online/TableDevise/components/defaultData';

export default (props: {
  tableConfig: OnlineType.OnlineTableType;
  setTableConfig: (newTableConfig: OnlineType.OnlineTableType) => void;
}) => {
  const { tableConfig, setTableConfig } = props;
  // CRUD 表单REF
  const crudFormRef = useRef<ProFormInstance>();

  // CRUD 配置
  const crudColumns: ProFormColumnsType<OnlineType.CrudConfig>[] = [
    {
      title: '数据表名称',
      dataIndex: 'sqlTableName',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入数据表名称',
      },
      tooltip: '必填，数据库数据表的完整名称',
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
      tooltip: '请使用大驼峰命名，自动携带Model、Controller等后缀',
    },
    {
      title: '控制器目录',
      dataIndex: 'controllerPath',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入路径',
        addonBefore: 'app/Http/Controllers/Admin/',
      },
      tooltip: '将生成（app/Http/Controllers/Admin/ + 输入路径 + 文件名 + Controller.php）文件， 如果在控制器根目录则省略不填',
    },
    {
      title: '模型目录',
      dataIndex: 'modelPath',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入模型路径',
        addonBefore: 'app/Models/',
      },
      tooltip: '将生成（app/Models/ + 输入路径 + 文件名 + Model.php）文件， 如果在模型根目录则省略不填',
    },
    {
      title: '前端页面目录',
      dataIndex: 'pagePath',
      valueType: 'text',
      fieldProps: {
        placeholder: '请输入前端页面目录',
        addonBefore: 'web/src/pages/',
      },
      tooltip: '将生成（web/src/pages/ + 输入路径 + 文件夹（文件名） + index.tsx）文件，文件目录即为URL访问目录',
    },
    {
      title: '权限标识',
      dataIndex: 'ruleName',
      valueType: 'text',
      tooltip: '权限标识，一般为路由路径 将 ‘/’ 替换为 ‘.’'
    },
    {
      valueType: 'switch',
      title: '自动时间戳',
      dataIndex: 'autoTime',
      colProps: {span: 4},
    },
    {
      valueType: 'switch',
      title: '开启软删除',
      dataIndex: 'autoDeletetime',
      colProps: {span: 4}
    },
  ];

  // 编辑字段 去抖配置
  const updateCRUDConfig = useDebounceFn(async (state) => {
    setTableConfig({ ...tableConfig, crudConfig: state });
  }, 300);

  useEffect(() => {
    crudFormRef.current?.setFieldsValue(tableConfig.crudConfig);
  }, [tableConfig]);

  return (
    <BetaSchemaForm<OnlineType.CrudConfig>
      onValuesChange={(record, recordList) => {
        updateCRUDConfig.run(recordList).then(() => {
        });
      }}
      layoutType={'Form'}
      layout={'vertical'}
      colProps={{ span: 8 }}
      rowProps={{gutter: 20}}
      grid={true}
      initialValues={{ ...defaultCRUDConfig, ...tableConfig.crudConfig }}
      formRef={crudFormRef}
      columns={crudColumns}
      submitter={{ render: () => <></> }}
    />
  );
}
