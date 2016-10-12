<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
            ['name' => '责任编辑', 'slug' => 'responsible_editor']
        );
        \Encore\Admin\Auth\Database\Role::create(
            ['name' => '编辑', 'slug' => 'editor']
        );
    }
}
