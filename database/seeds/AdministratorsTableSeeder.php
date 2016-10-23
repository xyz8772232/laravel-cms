<?php

use Illuminate\Database\Seeder;

class AdministratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Encore\Admin\Auth\Database\Administrator::create(
            ['username' => '812250076@qq.com', 'password' => bcrypt('xiaotie'), 'name' => 'xiaotie']
        );
    }
}
