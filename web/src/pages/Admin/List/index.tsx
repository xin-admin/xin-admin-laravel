import XinTable from '@/components/XinTable'
import {ProFormColumnsAndProColumns} from '@/components/XinTable/typings';
import XinDict from "@/components/XinDict";
import {useModel} from '@umijs/max';
import { Avatar, message, Popconfirm } from 'antd';
import UploadImgItem from "@/components/XinForm/UploadImgItem";
import React, {useRef} from 'react';
import UpdatePassword from './components/UpdatePassword';
import {deleteApi, listApi} from '@/services/common/table';
import {ActionType} from '@ant-design/pro-components';
import ButtonAccess from '@/components/ButtonAccess';

const api = '/admin/list';

interface ResponseAdminList {
    id?: number
    username?: string
    nickname?: string
    avatar?: string
    avatar_url?: string
    email?: string
    mobile?: string
    status?: number
    group_id?: number
    sex?: number
    create_time?: string
    update_time?: string
}


const Table: React.FC = () => {

    const {dictEnum} = useModel('dictModel')

    const columns: ProFormColumnsAndProColumns<ResponseAdminList>[] = [
        {
            title: '用户ID',
            dataIndex: 'id',
            hideInForm: true,
            hideInSearch: true,
            sorter: true,
        },
        {
            title: '搜索用户',
            dataIndex: 'keywordSearch',
            hideInForm: true,
            hideInTable: true,
            fieldProps: {
              placeholder: '输入ID\\账号\\昵称\\手机号\\邮箱搜索'
            }
        },
        {
            title: '用户名',
            dataIndex: 'username',
            valueType: 'text',
            hideInSearch: true,
            formItemProps: {rules: [{required: true, message: '该项为必填'}]},
        },
        {
            title: '昵称',
            dataIndex: 'nickname',
            valueType: 'text',
            hideInSearch: true,
            formItemProps: {rules: [{required: true, message: '该项为必填'}]},
            colProps: {md: 7,},
        },
        {
            title: '性别',
            dataIndex: 'sex',
            valueType: 'radio',
            valueEnum: dictEnum.get('sex'),
            render: (_, date) => <XinDict value={date.sex} dict={'sex'}/>,
            colProps: {md: 6,},
            filters: true,
            hideInSearch: true
        },
        {
            title: '邮箱',
            dataIndex: 'email',
            valueType: 'text',
            hideInSearch: true,
            formItemProps: {rules: [{required: true, message: '该项为必填'}]},
            colProps: {md: 6,},
        },
        {
            title: '管理员分组',
            dataIndex: 'group_id',
            valueType: 'treeSelect',
            formItemProps: {
                rules: [{required: true, message: '该项为必填'}],
            },
            fieldProps: {
                fieldNames: {label: 'name', value: 'id', children: 'children'},
            },
            request: async () => {
                let res = await listApi('/admin/group')
                return res.data.data
            },
            colProps: {md: 6,},
        },
        {
            title: '状态',
            dataIndex: 'status',
            valueType: 'radioButton',
            valueEnum: {
                0: {
                    text: '禁用',
                    status: 'Error',
                },
                1: {
                    text: '启用',
                    status: 'Success',
                },
            },
            formItemProps: {rules: [{required: true, message: '该项为必填'}]},
            colProps: {md: 6,},
            filters: true,
            hideInSearch: true
        },
        {
            title: '手机号',
            dataIndex: 'mobile',
            valueType: 'text',
            hideInSearch: true,
            formItemProps: {rules: [{required: true, message: '该项为必填'}]},
            colProps: {md: 6,},
        },
        {
            title: '头像',
            dataIndex: 'avatar_url',
            hideInSearch: true,
            valueType: 'avatar',
            hideInForm: true,
            render: (dom, entity) => <Avatar src={entity.avatar_url}></Avatar>
        },
        {
            title: '头像',
            dataIndex: 'avatar_id',
            hideInSearch: true,
            valueType: 'avatar',
            hideInTable: true,
            renderFormItem: (schema, config, form) => {
                return <UploadImgItem
                    form={form}
                    dataIndex={'avatar_id'}
                    api={'admin/admin/upAvatar'}
                    defaultFile={form.getFieldValue('avatar_url')}
                    crop={true}
                />
            },
            colProps: {md: 12,},
        },
        {
            valueType: 'dependency',
            hideInTable: true,
            hideInSearch: true,
            name: ['id'],
            columns: ({id}) => {
                if (!id) {
                    return [
                        {
                            title: '密码',
                            dataIndex: 'password',
                            valueType: 'password',
                            formItemProps: {rules: [{required: true, message: '该项为必填'}]},
                            colProps: {md: 6,},
                        },
                        {
                            title: '确认密码',
                            dataIndex: 'rePassword',
                            valueType: 'password',
                            formItemProps: {rules: [{required: true, message: '该项为必填'}]},
                            colProps: {md: 6,},
                        },
                    ]
                }
                return []
            }
        },
        {
            valueType: 'date',
            title: '创建时间',
            hideInForm: true,
            hideInTable: true,
            dataIndex: 'created_at',
        },
    ];

    const actionRef = useRef<ActionType>();
    /**
     *  删除节点
     * @param selectedRows
     */
    const handleRemove = async (selectedRows: ResponseAdminList[]) => {
        const hide = message.loading('正在删除');
        if (!selectedRows) {
            message.warning('请选择需要删除的节点');
            return
        }
        let ids = selectedRows.map(x => x.id)
        deleteApi(api + '/delete', {ids: ids.join() || ''}).then(res => {
            if (res.success) {
                message.success(res.msg);
                actionRef.current?.reloadAndRest?.();
            } else {
                message.warning(res.msg);
            }
        }).finally(() => hide())
    }
    return (
        <XinTable<ResponseAdminList>
            headerTitle={'管理员列表'}
            tableApi={api}
            columns={columns}
            accessName={'admin.list'}
            deleteShow={false}
            actionRef={actionRef}
            optionProps={{
                fixed: 'right',
                width: 180
            }}
            operateRender={(record) => (
                <>
                    <ButtonAccess auth={'admin.list.resetPassword'}>
                        <UpdatePassword record={record}></UpdatePassword>
                    </ButtonAccess>
                    {record.id !== 1 ?
                        <ButtonAccess auth={'admin.list.delete'}>
                            <Popconfirm
                                title="Delete the task"
                                description="你确定要删除这条数据吗？"
                                onConfirm={() => {
                                    handleRemove([record])
                                }}
                                okText="确认"
                                cancelText="取消"
                            >
                                <a>删除</a>
                            </Popconfirm>
                        </ButtonAccess>
                        : null
                    }
                </>
            )}
        />
    )

}

export default Table
