<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menu items
    |--------------------------------------------------------------------------
    |
    | title:    item title.
    | url:      item url.
    | icon:     item icon, see https://fortawesome.github.io/Font-Awesome/icons/
    | children: subitems
    |
    */

    [
        'title' => '控制面板',
        'url'   => '/',
        'icon'  => 'fa-bar-chart'
    ],

    [
        'title' => '文章管理',
        'icon'  => 'fa-newspaper-o',
        'children' => [
            [
                'title' => '发表文章',
                'url'   => 'articles/create',
                'icon'  => 'fa-pencil-square-o',
            ],
            [
                'title' => '文章审核',
                'url'   => 'articles/create',
                'icon'  => 'fa-book',
            ],
        ],
    ],

    [
        'title' => '权限管理',
        'icon'  => 'fa-users',
        'children' => [
                [
                    'title' => '用户',
                    'url'   => 'auth/users',
                    'icon'  => 'fa-user',
                ],
                [
                    'title' => '角色',
                    'url'   => 'auth/roles',
                    'icon'  => 'fa-user',
                ],
                [
                    'title' => '权限',
                    'url'   => 'auth/permissions',
                    'icon'  => 'fa-user',
                ],
            ]
    ],
    [
        'title' => '频道管理',
        'url'   => 'channels',
        'icon'  => 'fa-tasks',
    ],
];
