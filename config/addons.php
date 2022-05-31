<?php

return [
    'autoload' => false,
    'hooks' => [
        'ems_send' => [
            0 => 'faems',
        ],
        'ems_notice' => [
            0 => 'faems',
        ],
        'admin_login_init' => [
            0 => 'loginbg',
        ],
    ],
    'route' => [
        '/third.html$' => 'third/index/index',
        '/third/connect/[:platform]' => 'third/index/connect',
        '/third/callback/[:platform]' => 'third/index/callback',
        '/third/bind/[:platform]' => 'third/index/bind',
        '/third/unbind/[:platform]' => 'third/index/unbind',
    ],
    'service' => [
    ],
];
