import {
  Button,
  Tree,
  Card,
  Form,
  Input,
  Space,
  Table,
  Tooltip,
  Popover,
  Dropdown,
  type TableProps,
  type TableColumnType,
  type TreeProps, ConfigProvider, Flex, Divider,
} from "antd";
import type {XinTableProps, XinTableInstance, RequestParams, FormMode} from "./typings";
import SearchForm from "./SearchForm";
import XinForm, {type XinFormRef} from "@/components/XinForm";
import {type ReactNode, useCallback, useEffect, useImperativeHandle, useMemo, useRef, useState} from "react";
import type {FormColumn} from "@/components/XinFormField/FieldRender/typings";
import {Delete, List, Create, Update} from "@/api/common/table.ts";
import {isArray, isEmpty, omit} from "lodash";
import type {SearchProps} from "antd/es/input";
import {
  BorderlessTableOutlined,
  BorderOutlined,
  ColumnHeightOutlined,
  DeleteOutlined,
  EditOutlined,
  PlusOutlined,
  ReloadOutlined,
  SearchOutlined,
  SettingOutlined
} from "@ant-design/icons";
import {useTranslation} from "react-i18next";
import type {DataNode} from "antd/es/tree";
import AuthButton from "@/components/AuthButton";

// 默认分页大小
const DEFAULT_PAGE_SIZE: number = 10;
// 默认页数
const DEFAULT_PAGE: number = 1;

export default function XinTable<T extends Record<string, any> = any>(props: XinTableProps<T>) {
  const {
    api,
    accessName,
    rowKey,
    columns,
    tableRef,

    formProps,
    modalProps,
    cardProps,
    searchProps,
    operateProps = {},
    pagination: customPagination = {},

    addShow = true,
    editShow = true,
    deleteShow = true,
    searchShow = true,
    operateShow = true,
    paginationShow = true,
    keywordSearchShow = true,

    toolBarRender: customToolBalRender,
    actionBarRender: customActionBarRender,
    operateRender: customOperateRender,

    handleRequest: customHandleRequest,
    requestParams: customRequestParams,
    handleFinish: customHandleFinish,
  } = props;

  const {t} = useTranslation();
  const formRef = useRef<XinFormRef<T>>(null);

  // 内部状态
  const [loading, setLoading] = useState<boolean>(true);
  const [dataSource, setDataSource] = useState<T[]>([]);
  const [total, setTotal] = useState<number>(0);
  const [requestParams, setRequestParams] = useState<RequestParams>({
    page: DEFAULT_PAGE,
    pageSize: DEFAULT_PAGE_SIZE,
  });
  const [density, setDensity] = useState<TableProps['size']>();
  const [bordered, setBordered] = useState<boolean>();

  const [searchRef] = Form.useForm<T>();
  const [columnsChecked, setColumnsChecked] = useState<any[]>([]);
  const [searchRender, setSearchRender] = useState<boolean>(false);

  // 表单模式状态
  const [formMode, setFormMode] = useState<FormMode>('create');
  const [formDefaultValues, setFormDefaultValues] = useState<T | undefined>(undefined);

  /** 表格请求 */
  const handleRequest = useCallback(async (params?: RequestParams) => {
    try {
      setLoading(true);
      const defaultParams: RequestParams = Object.assign({
        page: DEFAULT_PAGE,
        pageSize: DEFAULT_PAGE_SIZE,
      }, params);
      // 自定义参数处理
      const finalParams: RequestParams = customRequestParams ? customRequestParams(defaultParams) : defaultParams;
      // 自定义请求
      let listData: { data: T[]; total: number };
      if (customHandleRequest) {
        listData = await customHandleRequest(finalParams);
      } else {
        const { data } = await List<T>(api, finalParams);
        listData = data.data!;
      }
      setDataSource(listData.data);
      setTotal(listData.total);
    } finally {
      setLoading(false);
    }
  }, [api, customHandleRequest, customRequestParams]);

  /** 新增按钮点击 */
  const handleCreate = useCallback(() => {
    setFormMode('create');
    setFormDefaultValues(undefined);
    formRef.current?.resetFields();
    formRef.current?.open();
  }, []);

  /** 修改按钮点击 */
  const handleUpdate = useCallback((record: T) => {
    setFormMode('update');
    setFormDefaultValues(record);
    formRef.current?.setFieldsValue(record);
    formRef.current?.open();
  }, []);

  // 暴露方法到 tableRef
  useImperativeHandle(tableRef, (): XinTableInstance<T> => ({
    reload: async () => { await handleRequest(); },
    reset: async () => {
      searchRef.resetFields();
      setRequestParams({ page: 1, pageSize: 10 });
      await handleRequest({ page: 1, pageSize: 10 });
    },
    getDataSource: () => dataSource,
    setDataSource,
    getTotal: () => total,
    getLoading: () => loading,
    setLoading,
    setPageInfo: (page?, pageSize?) => {
      setRequestParams(prev => ({
        ...prev,
        ...(page !== undefined && { page }),
        ...(pageSize !== undefined && { pageSize }),
      }));
    },
    getForm: () => formRef.current,
    getSearchForm: () => searchRef,
  }));

  /** 初始化 */
  useEffect(() => { void handleRequest(); }, []);

  /** 处理表格变化 */
  const handleTableChange: TableProps<T>['onChange'] = async (newPagination, newFilters, newSorter) => {
    const params: RequestParams = {
      ...requestParams,
      page: newPagination.current ?? requestParams.page,
      pageSize: newPagination.pageSize ?? requestParams.pageSize,
    };
    // 处理筛选
    if (!isEmpty(newFilters)) {
      params.filterValues = newFilters;
    }
    // 处理排序
    if (newSorter && !isArray(newSorter) && !isEmpty(newSorter) && newSorter.field) {
      params.sorterValue = {
        field: String(newSorter.field),
        order: newSorter.order === 'ascend' ? 'asc' : 'desc',
      };
    } else {
      delete params.sorterValue;
    }
    setRequestParams(params);
    await handleRequest(params);
  };

  /** 快速搜索 */
  const handleKeywordSearch: SearchProps['onSearch'] = async (value: string) => {
    if( !value ) {
      window.$message?.warning(t('xin.table.keywordEmpty'));
      return;
    }
    const params: RequestParams = {
      ...requestParams,
      page: 1,
      keywordSearch: value,
    }
    setRequestParams(params);
    await handleRequest(params);
  };

  /** 快速搜索 Change */
  const keywordSearchChange: SearchProps['onChange'] = (e) => {
    if( !e.target.value ) {
      const params = { ...requestParams };
      delete params.keywordSearch;
      setRequestParams(params);
    } else {
      setRequestParams({
        ...requestParams,
        keywordSearch: e.target.value,
      });
    }
  };

  /** 搜索表单提交 */
  const handleSearch = async () => {
    const searchValues: T = searchRef.getFieldsValue();
    // 移除 空值
    Object.keys(searchValues).forEach((key) => {
      if (searchValues[key] === '' || searchValues[key] === undefined) {
        delete searchValues[key];
      }
    });
    await handleRequest({
      page: 1,
      ...requestParams,
      ...searchValues,
    });
  };

  /** 搜索列 */
  const searchColumn: FormColumn<T>[] = useMemo(() => {
    if(!searchShow) return [];
    return columns.filter((column) => column.hideInSearch !== true);
  }, [columns, searchShow]);

  /** 表单列 */
  const formColumn: FormColumn<T>[] = useMemo(() => {
    return columns.filter((column) => {
      if(formMode === 'update') {
        return column.hideInForm !== true && column.hideInUpdate !== true;
      } else {
        return column.hideInForm !== true && column.hideInCreate !== true;
      }
    });
  }, [columns, formMode]);

  /** 默认表格列 */
  const defaultTableColumns = useMemo(() => {
    return columns
      .filter((column) => column.hideInTable !== true && column.dataIndex)
      .map(column => omit(column, ['hideInTable', 'hideInForm', 'hideInSearch', 'search']));
  }, [columns]);

  /** 初始化列设置树数据 */
  useEffect(() => {
    const dataIndexList = defaultTableColumns.map(item => item.dataIndex!);
    setColumnsChecked(dataIndexList);
  }, [defaultTableColumns]);

  /** 表格操作列渲染 */
  const operateRender = (record: T): ReactNode[] => {
    // 编辑按钮渲染
    const editRender = (): ReactNode => {
      const show = typeof editShow === 'function' ? editShow(record) : editShow;
      return show ? (
        <AuthButton auth={props.accessName + '.update'} key={'update'}>
          <Tooltip title={t('xin.table.edit')}>
            <Button
              type="primary"
              icon={<EditOutlined />}
              size={'small'}
              onClick={() => handleUpdate(record)}
            />
          </Tooltip>
        </AuthButton>
      ) : null;
    }
    // 删除按钮渲染
    const deleteRender = (): ReactNode => {
      const show = typeof deleteShow === 'function' ? deleteShow(record) : deleteShow;
      return show ? (
        <AuthButton auth={props.accessName + '.delete'} key={'delete'}>
          <Tooltip title={t('xin.table.delete')}>
            <Button
              danger
              type="primary"
              icon={<DeleteOutlined />}
              size={'small'}
              onClick={() => handleDelete(record)}
            />
          </Tooltip>
        </AuthButton>
      ) : null;
    }

    if (customOperateRender) {
      return customOperateRender(record, {
        del: deleteRender(),
        edit: editRender()
      });
    }
    return [deleteRender(), editRender()];
  };

  /** 删除记录 */
  const handleDelete = async (record: T) => {
    window.$modal?.confirm({
      title: t('xin.table.deleteConfirm', { id: record[rowKey] }),
      okText: t('xin.table.deleteOk'),
      cancelText: t('xin.table.deleteCancel'),
      onOk: async () => {
        await Delete(api + `/${record[rowKey]}`);
        window.$message?.success(t('xin.table.deleteSuccess'));
        await handleRequest(requestParams);
      }
    })
  };

  /** 表单提交处理 */
  const handleFinish = async (values: T) => {
    try {
      formRef.current?.setLoading(true);
      // 如果有自定义 onFinish，优先调用
      if (customHandleFinish) {
          const result = await customHandleFinish(values, formMode, formRef, formDefaultValues);
        if (result) {
          await handleRequest(requestParams);
          formRef.current?.close();
        }
        return;
      }
      if (formMode === 'create') {
        await Create(api, values);
        window.$message?.success(t('xin.table.form.createSuccess'));
      } else {
        if (formDefaultValues && rowKey) {
          await Update(api + `/${formDefaultValues[rowKey]}`, values);
          window.$message?.success(t('xin.table.form.updateSuccess'));
        } else {
          window.$message?.error(t('xin.table.form.updateKeyUndefined'));
          return;
        }
      }
      await handleRequest(requestParams);
      formRef.current?.close();
    } finally {
      formRef.current?.setLoading(false);
    }
  };

  /** 密度设置菜单 */
  const densityMenu = {
    items: [
      {
        key: 'large',
        label: t('xin.table.density.default'),
        onClick: () => setDensity('large'),
      },
      {
        key: 'middle',
        label: t('xin.table.density.middle'),
        onClick: () => setDensity('middle'),
      },
      {
        key: 'small',
        label: t('xin.table.density.compact'),
        onClick: () => setDensity('small'),
      },
    ],
    selectedKeys: [density || 'large'],
  };

  /** 列设置树数据  */
  const columnTreeData: DataNode[] = useMemo(() => {
    const filteredColumns = columns.filter(item => item.hideInTable !== true && item.dataIndex);
    return filteredColumns
      .map((item) => ({
        key: String(item.dataIndex),
        title: item.title,
      }));
  }, [columns]);

  /** 列设置选中改变 */
  const columnSettingCheck: TreeProps['onCheck'] = (keys) => {
    if(isArray(keys)) {
      setColumnsChecked(keys);
    }
  }

  /** 最终表格列，计算排序以及显示状态 */
  const tableColumns: TableColumnType<T>[] = useMemo(() => {
    // 过滤出选中的列
    const filteredColumns = defaultTableColumns.filter(column =>
      columnsChecked.includes(column.dataIndex as any)
    );
    if(! operateShow) {
      return filteredColumns;
    }
    return [
      ...filteredColumns,
      {
        title: t('xin.table.operate'),
        key: 'operate',
        align: 'center',
        ...operateProps,
        render: (_, record: T) => (
          <Space>{...operateRender(record)}</Space>
        ),
      }
    ];
  }, [defaultTableColumns, columnsChecked, operateShow]);

  /** 分页配置 */
  const paginationProps: TableProps['pagination'] = {
    showQuickJumper: true,
    showSizeChanger: true,
    showTotal: (total) => t('xin.table.total', { total }),
    pageSize: requestParams.pageSize,
    ...customPagination,
    current: requestParams.page,
    total,
  }

  /** 顶部操作栏渲染 */
  const actionBarRender = (): ReactNode[] => {
    // 新增按钮渲染
    const addButtonRender = addShow ? (
      <AuthButton auth={accessName + '.create'}>
        <Button type="primary" onClick={handleCreate} icon={<PlusOutlined />}>{t('xin.table.add')}</Button>
      </AuthButton>
    ) : null;
    // 搜索按钮渲染
    const searchButtonRender = searchShow ? (
      <Button type="primary" icon={<SearchOutlined/>} onClick={() => setSearchRender(!searchRender)}>
        搜索
      </Button>
    ) : null;
    // 关键字搜索按钮渲染
    const keywordSearchRender = keywordSearchShow ? (
      <Input.Search
        onChange={keywordSearchChange}
        placeholder={t('xin.table.keywordPlaceholder')}
        style={{ width: 200 }}
        value={requestParams.keywordSearch}
        onSearch={handleKeywordSearch}
      />
    ) : null;
    // 自定义顶部操作栏渲染
    if (customActionBarRender) {
      return customActionBarRender({
        add: addButtonRender,
        search: searchButtonRender,
        keywordSearch: keywordSearchRender
      }).filter(Boolean);
    } else {
      return [addButtonRender, searchButtonRender, keywordSearchRender].filter(Boolean);
    }
  }

  /** 顶部工具栏渲染 */
  const toolBarRender = (): ReactNode[] => {
    // 刷新
    const reload = (
      <Tooltip title={'刷新表格'}>
        <Button
          type="text"
          icon={<ReloadOutlined />}
          onClick={() => handleRequest()}
        />
      </Tooltip>
    );
    // 密度设置
    const columnHeight = (
      <Dropdown menu={densityMenu} trigger={['click']}>
        <Button type="text" icon={<ColumnHeightOutlined />} />
      </Dropdown>
    );
    // 边框设置
    const hideBorder = (
      <Tooltip title={bordered ? t('xin.table.hideBorder') : t('xin.table.showBorder')}>
        <Button
          type="text"
          icon={bordered ? <BorderOutlined /> : <BorderlessTableOutlined />}
          onClick={() => setBordered(!bordered)}
        />
      </Tooltip>
    );
    // 列设置
    const columnSetting = (
      <Popover
        content={(
          <ConfigProvider
            theme={{
              components: { Tree: { switcherSize: 12 } },
            }}
          >
            <Tree
              icon={false}
              blockNode
              checkable={true}
              treeData={columnTreeData}
              selectable={false}
              checkedKeys={columnsChecked}
              onCheck={columnSettingCheck}
            />
          </ConfigProvider>

        )}
        trigger="click"
        placement="bottomRight"
        title={t('xin.table.columnSettings')}
      >
        <Tooltip title={t('xin.table.columnSettings')}>
          <Button type="text" icon={<SettingOutlined />} />
        </Tooltip>
      </Popover>
    );
    // 自定义顶部操作栏渲染
    if (customToolBalRender) {
      return customToolBalRender({reload, columnHeight, hideBorder, columnSetting}).filter(Boolean);
    } else {
      return [reload, columnHeight, hideBorder, columnSetting];
    }
  }

  return (
    <>
      <Card {...cardProps}>
        {/* 搜索表单 */}
        { searchRender && (
          <>
            <SearchForm<T>
              form={searchRef}
              columns={searchColumn}
              handleSearch={handleSearch}
              {...searchProps}
            />
            <Divider />
          </>
        )}
        {/* 操作栏 */}
        <Flex justify={'space-between'} align={'center'} style={{ marginBottom: 20 }}>
          <Space>{ ...actionBarRender() }</Space>
          <Space size={1}>{...toolBarRender()}</Space>
        </Flex>
        {/* 表格 */}
        <Table
          loading={loading}
          dataSource={dataSource}
          size={density}
          bordered={bordered}
          {...props}
          columns={tableColumns}
          rowKey={rowKey}
          onChange={handleTableChange}
          pagination={paginationShow ? paginationProps : false}
        />
      </Card>

      {/* 新增编辑表单 */}
      <XinForm
        {...formProps}
        columns={formColumn}
        formRef={formRef}
        layoutType="ModalForm"
        modalProps={{
          title: formMode === 'update' ? t('xin.table.form.editTitle') : t('xin.table.form.createTitle'),
          styles: { header: { marginBottom: 16 } },
          ...modalProps,
        }}
        onFinish={handleFinish}
      />
    </>
  );
}
