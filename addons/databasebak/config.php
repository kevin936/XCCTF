<?php

return [
    0 => [
        'name' => 'backupDir',
        'title' => '备份存放目录',
        'type' => 'string',
        'content' => [
        ],
        'value' => '../data/',
        'rule' => 'required',
        'msg' => '',
        'tip' => '备份目录,请使用相对目录',
        'ok' => '',
        'extend' => '',
    ],
    1 => [
        'name' => 'backupIgnoreTables',
        'title' => '备份忽略的表',
        'type' => 'string',
        'content' => [
        ],
        'value' => 'fa_admin_log',
        'rule' => '',
        'msg' => '',
        'tip' => '忽略备份的表,多个表以,进行分隔',
        'ok' => '',
        'extend' => '',
    ],
    2 => [
        'name' => '__tips__',
        'title' => '温馨提示',
        'type' => '',
        'content' => [
        ],
        'value' => '请做好数据库离线备份工作，建议此插件仅用于开发阶段，项目正式上线建议卸载此插件',
        'rule' => '',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => '',
    ],
];
