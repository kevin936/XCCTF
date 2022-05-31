define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'store/index' + location.search,
                    add_url: 'store/add',
                    edit_url: 'store/edit',
                    del_url: 'store/del',
                    multi_url: 'store/multi',
                    table: 'store',
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
                        {field: 'name', title: __('Name')},
                        {field: 'img', title: __('Img')},
                        {field: 'spe', title: __('Spe')},
                        {field: 'stock', title: __('Stock')},
                        {field: 'score', title: __('Score')},
                        {field: 'tip', title: __('Tip'), searchList: {"正品保证":__('正品保证'),"顺丰包邮":__('顺丰包邮'),"闪电发货":__('闪电发货'),"包邮到家":__('包邮到家'),"七天无理由退货":__('七天无理由退货')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'type', title: __('Type'), searchList: {"0":__('Type 0'),"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'quota', title: __('Quota')},
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