import { Button, Card, FormInstance, message, Popconfirm, Space } from 'antd';
import React, { ReactNode, useState } from 'react';
import { CardProps } from 'antd/es/card';
import {
  ActionType,
  BetaSchemaForm,
  EditableFormInstance,
  EditableProTable,
  ProColumns,
  ProFormColumnsType,
} from '@ant-design/pro-components';
import type { DragEndEvent } from '@dnd-kit/core';
import { DndContext, PointerSensor, useSensor, useSensors } from '@dnd-kit/core';
import { restrictToVerticalAxis } from '@dnd-kit/modifiers';
import {
  arrayMove,
  SortableContext,
  useSortable,
  verticalListSortingStrategy,
} from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { IColumnsType, IGenSettingType } from '@/domain/iGenerator';
import { FormattedMessage } from '@umijs/max';
import { useRequest } from 'ahooks';
import { dbTypes, valueTypes } from './utils';

interface RowProps extends React.HTMLAttributes<HTMLTableRowElement> {
  'data-row-key': string;
}

const RowRender: React.FC<Readonly<RowProps>> = (props) => {
  const { attributes, listeners, setNodeRef, transform, transition, isDragging } = useSortable({
    id: props['data-row-key'],
  });

  const style: React.CSSProperties = {
    ...props.style,
    transform: CSS.Translate.toString(transform),
    transition,
    cursor: 'move',
    ...(isDragging ? { position: 'relative', zIndex: 100 } : {}),
  };

  return <tr {...props} ref={setNodeRef} style={style} {...attributes} {...listeners} />;
};

// tab 配置
const tabList:  CardProps['tabList'] = [
  { key: '1', label: '基本配置' },
  { key: '2', label: '字段列表' },
  { key: '3', label: '数据库字段配置' }
]

export default () => {
  // -------------- state -------------------
  const [tabChange, setTabChange] = useState('1');
  const [baseColumns, setBaseColumns] = useState<IColumnsType[]>([]);
  const [baseColumnsEditableKeys, setBaseColumnsEditableKeys] = useState<React.Key[]>(() =>
    baseColumns.map((item) => item.id)
  );
  const [dbColumnsEditableKeys, setDbColumnsEditableKeys] = useState<React.Key[]>(() =>
    baseColumns.filter((item) => item.dbColumns).map((item) => item.id)
  );
  const sensors = useSensors(
    useSensor(PointerSensor, {
      activationConstraint: {
        // https://docs.dndkit.com/api-documentation/sensors/pointer#activation-constraints
        distance: 1,
      },
    }),
  );

  // -------------- Ref -----------------------
  const baseColumnsTableRef = React.useRef<EditableFormInstance>();
  const baseColumnsTableActionRef = React.useRef<ActionType>();
  const dbColumnsTableRef = React.useRef<EditableFormInstance>();
  const dbColumnsTableActionRef = React.useRef<ActionType>();

  // -------------- Columns -------------------
  const baseSettingColumns: ProFormColumnsType<IGenSettingType>[] = [
    {
      dataIndex: 'name',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.name'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.name.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'path',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.path'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.path.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'module',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.module'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.module.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'routePrefix',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.routePrefix'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.routePrefix.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'abilitiesPrefix',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.abilitiesPrefix'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.abilitiesPrefix.tooltip'} />
    },
    {
      valueType: 'select',
      dataIndex: 'quickSearchField',
      title: <FormattedMessage id={'gen.baseSetting.quickSearchField'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.quickSearchField.tooltip'} />,
      params: {columns: baseColumns},
      request: async (params: {columns: IColumnsType[]}) => {
        return params.columns.filter((item) => item.dbColumns).map(item => {
          return {
            label: item.name,
            value: item.name
          }
        })
      },
      fieldProps: {mode: "multiple"}
    },
    {
      dataIndex: 'crudRequest',
      valueType: 'checkbox',
      valueEnum: {
        create: <FormattedMessage id={'gen.baseSetting.crudRequest.create'} />,
        update: <FormattedMessage id={'gen.baseSetting.crudRequest.update'} />,
        delete: <FormattedMessage id={'gen.baseSetting.crudRequest.delete'} />,
        query: <FormattedMessage id={'gen.baseSetting.crudRequest.query'} />,
        find: <FormattedMessage id={'gen.baseSetting.crudRequest.find'} />
      },
      colProps: {span: 24},
      title: <FormattedMessage id={'gen.baseSetting.crudRequest'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.crudRequest.tooltip'} />
    },
    {
      valueType: 'text',
      dataIndex: 'pagePath',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.baseSetting.pagePath'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.pagePath.tooltip'} />
    },
    {
      dataIndex: 'page_is_file',
      valueType: 'switch',
      colProps: {span: 8},
      title: <FormattedMessage id={'gen.baseSetting.page_is_file'} />,
      tooltip: <FormattedMessage id={'gen.baseSetting.page_is_file.tooltip'} />
    },
  ];
  const baseColumnColumns: ProColumns<IColumnsType>[] = [
    {
      dataIndex: 'id',
      valueType: 'text',
      editable: false,
      width: 140,
      title: <FormattedMessage id={'gen.column.id'} />,
      tooltip: <FormattedMessage id={'gen.column.id.tooltip'} />,
    },
    {
      dataIndex: 'name',
      valueType: 'text',
      editable: false,
      title: <FormattedMessage id={'gen.column.name'} />,
      tooltip: <FormattedMessage id={'gen.column.name.tooltip'} />,
    },
    {
      dataIndex: 'title',
      valueType: 'text',
      editable: false,
      title: <FormattedMessage id={'gen.column.title'} />,
      tooltip: <FormattedMessage id={'gen.column.title.tooltip'} />,
    },
    {
      dataIndex: 'comment',
      valueType: 'text',
      editable: false,
      title: <FormattedMessage id={'gen.column.comment'} />,
      tooltip: <FormattedMessage id={'gen.column.comment.tooltip'} />,
    },
    {
      title: <FormattedMessage id={'gen.column.valueType'} />,
      dataIndex: 'valueType',
      valueType: 'select',
      tooltip: <FormattedMessage id={'gen.column.valueType.tooltip'} />,
      valueEnum: valueTypes,
      initialValue: "text",
      fieldProps: (form, { rowKey }) => {
        return {
          showSearch: true,
          filterOption: (input: string, option: any) => (option?.label ?? '').toLowerCase().includes(input.toLowerCase()),
        };
      }
    },
    {
      dataIndex: 'dbColumns',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.column.dbColumns'} />,
      tooltip: <FormattedMessage id={'gen.column.dbColumns.tooltip'} />,
    },
    {
      dataIndex: 'table',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.column.table'} />,
      tooltip: <FormattedMessage id={'gen.column.table.tooltip'} />,
    },
    {
      dataIndex: 'form',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.column.form'} />,
      tooltip: <FormattedMessage id={'gen.column.form.tooltip'} />,
    },
    {
      dataIndex: 'select',
      valueType: 'switch',
      title: <FormattedMessage id={'gen.column.select'} />,
      tooltip: <FormattedMessage id={'gen.column.select.tooltip'} />,
    },
    {
      valueType: 'option',
      width: 100,
      align: 'center',
      fixed: 'right',
      title: <FormattedMessage id={'gen.option'} />,
    },
  ];
  const addColumnColumns: ProFormColumnsType<IColumnsType>[] = [
    {
      dataIndex: 'title',
      valueType: 'text',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.column.title'} />,
      tooltip: <FormattedMessage id={'gen.column.title.tooltip'} />,
    },
    {
      dataIndex: 'comment',
      valueType: 'text',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.column.comment'} />,
      tooltip: <FormattedMessage id={'gen.column.comment.tooltip'} />,
    },
    {
      dataIndex: 'name',
      valueType: 'text',
      formItemProps: {rules: [{required: true}]},
      title: <FormattedMessage id={'gen.column.name'} />,
      tooltip: <FormattedMessage id={'gen.column.name.tooltip'} />,
    },
  ];
  const dbColumnColumns: ProColumns<IColumnsType>[] = [
    {
      dataIndex: 'name',
      valueType: 'text',
      editable: false,
      align: 'center',
      title: <FormattedMessage id={'gen.column.name'} />,
      tooltip: <FormattedMessage id={'gen.column.name.tooltip'} />,
    },
    {
      dataIndex: 'comment',
      valueType: 'text',
      editable: false,
      align: 'center',
      title: <FormattedMessage id={'gen.column.comment'} />,
      tooltip: <FormattedMessage id={'gen.column.comment.tooltip'} />,
    },
    {
      dataIndex: 'type',
      valueType: 'select',
      fieldProps: {
        showSearch: true,
        filterOption: (input: string, option: any) => (option?.label ?? '').toLowerCase().includes(input.toLowerCase())
      },
      valueEnum: dbTypes,
      initialValue: 'varchar',
      width: 130,
      align: 'center',
      title: <FormattedMessage id={'gen.column.type'} />,
      tooltip: <FormattedMessage id={'gen.column.type.tooltip'} />,
    },
    {
      dataIndex: 'default',
      valueType: 'text',
      width: 130,
      align: 'center',
      title: <FormattedMessage id={'gen.column.default'} />,
      tooltip: <FormattedMessage id={'gen.column.default.tooltip'} />,
    },
    {
      dataIndex: 'length',
      valueType: 'digit',
      width: 130,
      align: 'center',
      title: <FormattedMessage id={'gen.column.length'} />,
      tooltip: <FormattedMessage id={'gen.column.length.tooltip'} />,
      fieldProps: (form, {rowKey}) => disableColumn(form, rowKey,
        ["bigint", "int", "integer", "mediumint", "smallint", "tinyint", "char", "varchar", "binary", "varbinary"]
      ),
    },
    {
      dataIndex: 'notNull',
      valueType: 'switch',
      width: 100,
      align: 'center',
      title: <FormattedMessage id={'gen.column.notNull'} />,
      tooltip: <FormattedMessage id={'gen.column.notNull.tooltip'} />,
    },
    {
      dataIndex: 'unsigned',
      valueType: 'switch',
      width: 100,
      align: 'center',
      title: <FormattedMessage id={'gen.column.unsigned'} />,
      tooltip: <FormattedMessage id={'gen.column.unsigned.tooltip'} />,
      fieldProps: (form, {rowKey}) => disableColumn(form, rowKey,
        ["bigint", "int", "integer", "mediumint", "smallint", "tinyint", "float", "double"]
      ),
    },
    {
      dataIndex: 'autoincrement',
      valueType: 'switch',
      width: 110,
      align: 'center',
      title: <FormattedMessage id={'gen.column.autoincrement'} />,
      tooltip: <FormattedMessage id={'gen.column.autoincrement.tooltip'} />,
      fieldProps: (form, {rowKey}) => disableColumn(form, rowKey,
        ["bigint", "int", "integer", "mediumint", "smallint", "tinyint"]
      ),
    },
    {
      dataIndex: 'precision',
      valueType: 'digit',
      width: 130,
      align: 'center',
      title: <FormattedMessage id={'gen.column.precision'} />,
      tooltip: <FormattedMessage id={'gen.column.precision.tooltip'} />,
      fieldProps: (form, {rowKey}) => disableColumn(form, rowKey,
        ["decimal", "numeric"]
      ),
    },
    {
      dataIndex: 'scale',
      valueType: 'digit',
      width: 130,
      align: 'center',
      title: <FormattedMessage id={'gen.column.scale'} />,
      tooltip: <FormattedMessage id={'gen.column.scale.tooltip'} />,
      fieldProps: (form, {rowKey}) => disableColumn(form, rowKey,
        ["decimal", "numeric"]
      ),
    },
    {
      dataIndex: 'presetValues',
      valueType: 'text',
      width: 140,
      align: 'center',
      title: <FormattedMessage id={'gen.column.presetValues'} />,
      tooltip: <FormattedMessage id={'gen.column.presetValues.tooltip'} />,
      fieldProps: (form, {rowKey}) => {
        return disableColumn(form, rowKey, ['enum', 'set']);
      }
    },
    {
      valueType: 'option',
      width: 100,
      align: 'center',
      fixed: 'right',
      title: <FormattedMessage id={'gen.option'} />,
    },
  ];

  // -------------- Function -------------------
  const addBaseColumn = async (values: IColumnsType) => {
    if(baseColumns.find((item) => item.name === values.name)) {
      message.error('字段已存在');
      return false;
    }
    let dataSource: IColumnsType[] = baseColumns.concat({
      id: Date.now().toString(),
      name: values.name,
      title: values.title,
      comment: values.comment,
      select: true,
      form: true,
      table: true,
      dbColumns: true,
    })
    setBaseColumns(dataSource);
    setBaseColumnsEditableKeys(dataSource.map((item) => item.id));
    setDbColumnsEditableKeys(dataSource.filter((item) => item.dbColumns).map((item) => item.id))
    return true;
  }
  const updateValues = async (record: IColumnsType) => {
    let dataSource: IColumnsType[] = baseColumns.map((item) => {
      if(item.id === record.id) {return { ...item, ...record }}
      return item
    });
    console.log(dataSource);
    setBaseColumns(dataSource);
    setBaseColumnsEditableKeys(dataSource.map((item) => item.id));
    setDbColumnsEditableKeys(dataSource.filter((item) => item.dbColumns).map((item) => item.id))
  }
  const { run: onValuesChange } = useRequest(updateValues, {
    debounceWait: 300,
    manual: true,
  });
  const deleteColumn = async (record: IColumnsType) => {
    let dataSource: IColumnsType[] = baseColumns.filter((item) => item.id !== record.id);
    setBaseColumns(dataSource);
    setBaseColumnsEditableKeys(dataSource.map((item) => item.id));
    setDbColumnsEditableKeys(dataSource.filter((item) => item.dbColumns).map((item) => item.id))
  }
  const disableColumn = (form: FormInstance, rowKey: string | undefined, types: string[]) => {
    let valueType = form?.getFieldValue([rowKey, 'type']);
    if(! valueType ) {
      return { disabled: true }
    }else if(! types.includes(valueType)) {
      return { disabled: true }
    }
  }
  const actionRender = (record: IColumnsType) => [
    <Popconfirm
      okText="确认"
      cancelText="取消"
      title="Delete the task"
      description="你确定要删除这条数据吗？"
      onConfirm={() => deleteColumn(record)}
    >
      <a>删除</a>
    </Popconfirm>
  ]
  const onDragEnd = ({ active, over }: DragEndEvent) => {
    if (active.id !== over?.id) {
      setBaseColumns((prev) => {
        const activeIndex = prev.findIndex((i) => i.id === active.id);
        const overIndex = prev.findIndex((i) => i.id === over?.id);
        return arrayMove(prev, activeIndex, overIndex);
      });
    }
  };
  const tableViewRender = (_: any, dom: ReactNode) => (<>
    <DndContext sensors={sensors} modifiers={[restrictToVerticalAxis]} onDragEnd={onDragEnd}>
      <SortableContext
        items={baseColumns.map((i) => i.id)}
        strategy={verticalListSortingStrategy}
      > { dom }
      </SortableContext>
    </DndContext>
  </>)

  // -------------- Element -------------------
  const tabBarExt = (<>
    <Space>
      <BetaSchemaForm<IColumnsType>
        trigger={<Button color="primary" variant="solid">新增字段</Button>}
        shouldUpdate={false}
        layoutType="ModalForm"
        onFinish={addBaseColumn}
        columns={addColumnColumns}
        initialValues={{
          title: '姓名',
          name: "name",
          comment: "姓名",
        }}
      />
      <Button color="purple" variant="solid">AI一键生成字段</Button>
      <Button color="purple" variant="solid">导入数据库字段</Button>
      <Button color="magenta" variant="solid">生成预览</Button>
      <Button color="default" variant="solid">一键生成代码</Button>
    </Space>
  </>);
  const baseSettingForm = (<>
    <BetaSchemaForm<IGenSettingType>
      shouldUpdate={false}
      layoutType="Form"
      onFinish={async (values) => {
        console.log(values);
      }}
      initialValues={{
        page_is_file: false,
        crudRequest: ['create', 'update', 'delete', 'query', 'find']
      }}
      grid
      colProps={{ span: 12 }}
      rowProps={{ gutter: [40, 0] }}
      columns={baseSettingColumns}
      style={{width: 800}}
      onValuesChange={(data, allValues) => {
        console.log(allValues);
      }}
      submitter={{
        render: (props) => {
          return [
            <Button type='default' key="rest" onClick={() => props.form?.resetFields()}>
              重置表单
            </Button>,
            <Button type="primary" key="preview" onClick={() => {}}>
              预览文件结果
            </Button>,
          ];
        },
      }}
    />
  </>);
  const baseColumnsTable = (<>
    <EditableProTable<IColumnsType>
      columns={baseColumnColumns}
      rowKey="id"
      size={'large'}
      bordered={true}
      scroll={{ x: 800 }}
      value={baseColumns}
      toolBarRender={false}
      recordCreatorProps={false}
      tableViewRender={tableViewRender}
      components={{ body: { row: RowRender } }}
      editableFormRef={baseColumnsTableRef}
      actionRef={baseColumnsTableActionRef}
      editable={{
        type: 'multiple',
        editableKeys: baseColumnsEditableKeys,
        actionRender,
        onValuesChange,
        onChange: setBaseColumnsEditableKeys,
      }}
    />
  </>);
  const dbColumnsTable = (<>
    <EditableProTable<IColumnsType>
      columns={dbColumnColumns}
      rowKey="id"
      size={'large'}
      bordered={true}
      scroll={{ x: 1400 }}
      style={{minHeight: 500}}
      value={baseColumns}
      toolBarRender={false}
      components={{ body: { row: RowRender } }}
      recordCreatorProps={false}
      editableFormRef={dbColumnsTableRef}
      actionRef={dbColumnsTableActionRef}
      tableViewRender={tableViewRender}
      editable={{
        type: 'multiple',
        editableKeys: dbColumnsEditableKeys,
        onValuesChange,
        actionRender,
        onChange: setDbColumnsEditableKeys,
      }}
    />
  </>);

  return (<>
    <Card
      tabBarExtraContent={tabBarExt}
      tabList={tabList}
      activeTabKey={tabChange}
      onTabChange={setTabChange}
      defaultActiveTabKey={'1'}
      loading={false}
      style={ { minWidth: 1080 }}
      styles={{ body: { minHeight: 500}
      }}
    >
      { tabChange === '1' && baseSettingForm }
      { tabChange === '2' && baseColumnsTable }
      { tabChange === '3' && dbColumnsTable }
    </Card>
  </>)
}