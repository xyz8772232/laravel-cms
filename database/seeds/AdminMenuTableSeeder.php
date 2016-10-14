<?php

use Illuminate\Database\Seeder;
use \Encore\Admin\Auth\Database\Menu;

class AdminMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => '控制面板',
                'icon'      => 'fa-bar-chart',
                'uri'       => '/',
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => '权限控制',
                'icon'      => 'fa-user',
                'uri'       => '',
            ],
            [
                'parent_id' => 2,
                'order'     => 3,
                'title'     => '用户',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 2,
                'order'     => 4,
                'title'     => '角色',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'parent_id' => 2,
                'order'     => 5,
                'title'     => '权限',
                'icon'      => 'fa-user',
                'uri'       => 'auth/permissions',
            ],
            [
                'parent_id' => 2,
                'order'     => 6,
                'title'     => '菜单',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
            ],
            [
                'parent_id' => 0,
                'order'     => 7,
                'title'     => '文章',
                'icon'      => 'fa-book',
                'uri'       => '',
            ],
            [
                'parent_id' => 7,
                'order'     => 8,
                'title'     => '发表文章',
                'icon'      => 'fa-pencil-square-o',
                'uri'       => 'articles/create',
            ],
            [
                'parent_id' => 7,
                'order'     => 9,
                'title' => '文章审核',
                'icon'  => 'fa-newspaper-o',
                'url'   => 'articles',
            ],
            [
                'parent_id' => 7,
                'order'     => 10,
                'title' => '文字链接',
                'url'   => 'link',
                'icon'  => 'fa-link',
            ],
        ]);
    }
}
