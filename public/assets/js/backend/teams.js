define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'teams/index' + location.search,
                    add_url: 'teams/add',
                    edit_url: 'teams/edit',
                    del_url: 'teams/del',
                    multi_url: 'teams/multi',
                    table: 'teams',
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
                        {field: 'team_name', title: __('Team_name')},
                        {field: 'team_score', title: __('Team_score')},
                        {field: 'team_leader', title: __('Team_leader')},
                        {field: 'team_number', title: __('Team_number')},
                        {field: 'team_status', title: __('Team_status'), searchList: {"0":__('Team_status 0'),"1":__('Team_status 1'),"2":__('Team_status 2')}, formatter: Table.api.formatter.status},
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