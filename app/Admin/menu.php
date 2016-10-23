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
        'title' => '权限控制',
        'icon'  => 'fa-user',
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
        'title' => '文章',
        'icon'  => 'fa-book',
        'children' => [
            [
                'title' => '发表文章',
                'url'   => 'articles/create',
                'icon'  => 'fa-pencil-square-o',
            ],
            [
                'title' => '文章审核',
                'url'   => 'articles',
                'icon'  => 'fa-newspaper-o',
            ],
            [
                'title' => '操作日志',
                'url'   => 'article_logs',
                'icon'  => 'fa-link',
            ],
        ],
    ],

    [
        'title' => '热区排序',
        'icon' => 'fa-sort',
        'children' => [
            [
                'title' => '文字链接',
                'url'   => 'sortlinks',
                'icon'  => 'fa-rocket',
            ],
            [
                'title' => '焦点图',
                'url'   => 'sortphotos',
                'icon'  => 'fa-plane',
            ],
        ],
    ],

    [
        'title' => '用户互动',
        'icon' => 'fa-users',
        'children' => [
            [
                'title' => '评论',
                'url' => 'comments',
                'icon' => 'fa-comment',
            ],

            [
                'title' => '投票',
                'url' => 'votes',
                'icon' => 'fa-calculator',
            ],
        ]
    ],

    [
        'title' => '素材',
        'icon' => 'fa-image',
        'children' => [
            [
                'title' => '图片',
                'url'   => 'photos',
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
        'title' => '频道',
        'url'   => 'channels',
        'icon'  => 'fa-tasks',
    ],
    [
        'title' => '关键词',
        'url'   => 'keywords',
        'icon'  => 'fa-info',
    ],
    [
        'title' => 'APP',
        'icon' => 'fa-linux',
        'children' => [
            [
                'title' => '启动幻灯片',
                'url'   => 'app_photos',
                'icon'  => 'fa-rocket',
            ],
            [
                'title' => '消息推送',
                'url'   => 'app_messages',
                'icon'  => 'fa-plane',
            ],
        ],
    ],
];
