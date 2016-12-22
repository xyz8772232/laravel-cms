<?php

use Illuminate\Database\Seeder;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Menu;

class AdministratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username'  => 'admin',
            'password'  => env('ADMIN_PASSWORD') ? : bcrypt('admin'),
            'name'      => 'Administrator',
        ]);
        Administrator::create([
            'username' => '812250076@qq.com',
            'password' => bcrypt('xiaotie'),
            'name' => 'xiaotie',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name'  => '管理员',
            'slug'  => 'administrator',
        ]);
        Role::create([
                'name' => '责任编辑',
                'slug' => 'responsible_editor'
        ]);
        Role::create([
                'name' => '编辑',
                'slug' => 'editor'
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '网站首页',
                'icon' => 'fa-sort',
                'uri' => '',
            ],
            [
                'parent_id' => 1,
                'order' => 2,
                'title' => '新闻排序',
                'icon' => 'fa-rocket',
                'uri' => 'sort_links',
            ],
            [
                'parent_id' => 1,
                'order' => 3,
                'title' => '幻灯片排序',
                'icon' => 'fa-plane',
                'uri' => 'sort_photos',
            ],
            [
                'parent_id' => 0,
                'order' => 4,
                'title' => '新闻',
                'icon' => 'fa-bar-chart',
                'uri' => '',
            ],
            [
                'parent_id' => 0,
                'order' => 5,
                'title' => '待审核',
                'icon' => 'fa-book',
                'uri' => 'articles/audit_list',
            ],
            [
                'parent_id' => 0,
                'order' => 6,
                'title' => '素材',
                'icon' => 'fa-photo',
                'uri' => 'photos',
            ],
            [
                'parent_id' => 0,
                'order' => 7,
                'title' => '系统',
                'icon' => 'fa-gear',
                'uri' => '',
            ],
            [
                'parent_id' => 6,
                'order' => 8,
                'title' => '权限控制',
                'icon' => 'fa-user',
                'uri' => '',
            ],
            [
                'parent_id' => 7,
                'order' => 9,
                'title' => '用户',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
            ],
            [
                'parent_id' => 7,
                'order' => 10,
                'title' => '角色',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
            ],
            [
                'parent_id' => 7,
                'order' => 11,
                'title' => '权限',
                'icon' => 'fa-user',
                'uri' => 'auth/permissions',
            ],
            [
                'parent_id' => 7,
                'order' => 12,
                'title' => '菜单',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
            ],
            [
                'parent_id' => 6,
                'order' => 13,
                'title' => '频道',
                'icon' => 'fa-tasks',
                'uri' => 'channels',
            ],
            [
                'parent_id' => 6,
                'order' => 14,
                'title' => '关键词',
                'icon' => 'fa-info',
                'url' => 'keywords',
            ],
            [
                'parent_id' => 6,
                'order' => 15,
                'title' => '水印',
                'icon' => 'fa-image',
                'url' => 'watermarks',
            ],
            [
                'parent_id' => 6,
                'order' => 16,
                'title' => '投票',
                'icon' => 'fa-calculator',
                'uri' => 'ballots',
            ],
            [
                'parent_id' => 6,
                'order' => 17,
                'title' => '文章日志',
                'icon' => 'fa-history',
                'uri' => 'logs',
            ],
            [
                'parent_id' => 6,
                'order' => 18,
                'title' => '评论',
                'uri' => 'comments',
                'icon' => 'fa-comment',
            ],
            [
                'parent_id' => 0,
                'order' => 19,
                'title' => 'APP',
                'icon' => 'fa-bars',
                'uri' => 'watermarks',

            ],
            [
                'parent_id' => 18,
                'order' => 20,
                'title' => '消息推送',
                'icon' => 'fa-bars',
                'uri' => 'app_messages',
            ],
            [
                'parent_id' => 18,
                'order' => 21,
                'title' => '启动幻灯片',
                'icon' => 'fa-bars',
                'uri' => 'app_photos',
            ],
            [
                'parent_id' => 18,
                'order' => 22,
                'title' => '网友报料',
                'icon' => 'fa-bars',
                'uri' => 'exposures',
            ],
        ]);

        // add role to menu.
        Menu::find(6)->roles()->save(Role::first());
        Menu::find(4)->roles()->save(Role::first());
        Menu::find(4)->roles()->save(Role::find(2));
    }
}
