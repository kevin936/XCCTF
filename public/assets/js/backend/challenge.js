define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'challenge/index' + location.search,
                    add_url: 'challenge/add',
                    edit_url: 'challenge/edit',
                    del_url: 'challenge/del',
                    multi_url: 'challenge/multi',
                    table: 'challenge',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'topic_name', title: __('Topic_name')},
                        {field: 'topic_type', title: __('Topic_type')},
                        {field: 'topic_score', title: __('Topic_score')},
                        {field: 'topic_flag', title: __('Topic_flag'), formatter: Table.api.formatter.flag},
                        {field: 'topic_difficulty', title: __('Topic_difficulty'), searchList: {"1":__('Topic_difficulty 1'),"2":__('Topic_difficulty 2'),"3":__('Topic_difficulty 3')}, formatter: Table.api.formatter.normal},
                        {field: 'topic_succeed', title: __('Topic_succeed')},
                        {field: 'topic_status', title: __('Topic_status'), searchList: {"0":__('Topic_status 0'),"1":__('Topic_status 1')}, formatter: Table.api.formatter.status},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});