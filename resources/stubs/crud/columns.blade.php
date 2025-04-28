const columns: XinTableColumn<{{ $domainName  }}>[] = [
@foreach($columns as $column)
    {
    @isset($column['key'])
        key: '{{ $column['key'] }}',
    @endisset
    @isset($column['title'])
        title: '{{ $column['title'] }}',
    @endisset
    @isset($column['valueType'])
        valueType: '{{ $column['valueType'] }}',
    @endisset
    @isset($column['dataIndex'])
        dataIndex: '{{ $column['dataIndex'] }}',
    @endisset
    @isset($column['tooltip'])
        tooltip: {{ $column['tooltip'] }},
    @endisset
    @isset($column['valueEnum'])
        valueEnum: {
        @foreach($column['valueEnum'] as $key => $value)
            '{{ $key }}': {
            text: '{{ $value['text'] }}',
            status: '{{ $value['status'] }}',
            },
        @endforeach
        }
    @endisset
    @isset($column['renderText'])
        // 自定义表格文字渲染
        renderText: (text, record) => {!! '<></>' !!},
    @endisset
    @isset($column['render'])
        // 自定义表格渲染
        render: (text, record) => {!! '<></>' !!},
    @endisset
    },
    @isset($column['renderFormItem'])
        // 自定义表单渲染
        renderFormItem: (_, { type, defaultRender, ...rest }, form) => {!! '<></>' !!},
    @endisset
    @isset($column['hideInForm'])
        hideInForm: {{ $column['hideInForm'] ? 'true' : 'false' }},
    @endisset
    @isset($column['hideInTable'])
        hideInTable: {{ $column['hideInTable'] ? 'true' : 'false' }},
    @endisset
    @isset($column['hideInSearch'])
        hideInSearch: {{ $column['hideInSearch'] ? 'true' : 'false' }},
    @endisset
    @isset($column['hideInDescriptions'])
        hideInDescriptions: {{ $column['hideInDescriptions'] ? 'true' : 'false' }},
    @endisset
@endforeach
];