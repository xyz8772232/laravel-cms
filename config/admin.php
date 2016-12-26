<?php

return [

    /*
     * Laravel-admin name.
     */
    'name'  => 'CMS后台管理系统',
    /*
     * Laravel-admin url prefix.
     */
    'prefix'    => 'admin',

    /*
     * Laravel-admin install directory.
     */
    'directory' => app_path('Admin'),

    /*
     * Laravel-admin title.
     */
    'title'  => 'Admin',

    'auth' => [
        'driver'   => 'session',
        'provider' => '',
        'model'    => Encore\Admin\Auth\Database\Administrator::class,
    ],

    /*
     * Laravel-admin upload setting.
     */
    'upload'  => [

        'disk' => 'admin',

        'directory'  => [
            'image'  => 'image',
            'file'   => 'file',
        ],

        //'host' => 'http://yun.app/upload/',
        'host' => 'http://59.110.23.172/upload/',
    ],

    /*
     * Laravel-admin database setting.
     */
    'database' => [
        'users_table' => 'admin_users',
        'users_model' => Encore\Admin\Auth\Database\Administrator::class,

        'roles_table' => 'admin_roles',
        'roles_model' => Encore\Admin\Auth\Database\Role::class,

        'permissions_table' => 'admin_permissions',
        'permissions_model' => Encore\Admin\Auth\Database\Permission::class,

        'menu_table'  => 'admin_menu',
        'menu_model'  => Encore\Admin\Auth\Database\Menu::class,

        'operation_log_table'    => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table'       => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table'        => 'admin_role_menu',
    ],

    /*
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-black                              |
    |               | skin-purple                             |
    |               | skin-yellow                             |
    |               | skin-red                                |
    |               | skin-green                              |
    |---------------------------------------------------------|
     */
    'skin'    => 'skin-yellow',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout'  => ['sidebar-mini'],

    'version'   => '1.0',

    'local_uri_str' => '<!--CMS_LOCAL_URI-->',
    'image_str' => '<!--IMAGE_PATH-->',
    'image_url' => 'http://img.yunapp.mobi/upload/',
    'image_host' => 'http://img.yunapp.mobi',
    //'image_url' => 'http://59.110.23.172/upload/',
    //'image_host' => 'http://59.110.23.172',

    'admin_editors' => ['administrator', 'responsible_editor'],

    'news_column' => ['name' => '新闻', 'order' => 1],
];
