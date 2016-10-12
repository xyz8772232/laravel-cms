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
                'url'   => 'articles',
                'icon'  => 'fa-book',
            ],
        ],
    ],

    [
        'title' => '评论管理',
        'url' => 'comments',
        'icon' => 'fa-comment',
    ],

    [
        'title' => '投票管理',
        'url' => 'votes',
        'icon' => 'fa-calculator',
    ],

    [
        'title' => '热区排序',
        'icon' => 'fa-sort',
        'children' => [
            [
                'title' => '文字链接',
                'url'   => 'linksort',
                'icon'  => 'fa-rocket',
            ],
            [
                'title' => '焦点图',
                'url'   => 'picsort',
                'icon'  => 'fa-plane',
            ],
        ],
    ],

    [
        'title' => '素材管理',
        'icon' => 'fa-image',
        'children' => [
            [
                'title' => '图片',
                'url'   => 'pictures',
                'icon'  => 'fa-image',
            ],
            [
                'title' => '水印',
                'url'   => 'watermarks',
                'icon'  => 'fa-image',
            ],
        ],
    ],
    [
        'title' => '频道管理',
        'url'   => 'channels',
        'icon'  => 'fa-tasks',
    ],
    [
        'title' => '关键词',
        'url'   => 'keywords',
        'icon'  => 'fa-info',
    ],

    [
        'title' => '系统日志',
        'url'   => 'syslog',
        'icon'  => 'fa-linux',
    ],
];
