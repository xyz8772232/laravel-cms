<?php

use Illuminate\Database\Seeder;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Channel::truncate();
        \App\Channel::create(
            ['name' => '新闻', 'admin_user_id' => 1, 'grade' => 1, 'parent_id' => 0]
        );
        \App\Channel::create(
            ['name' => '新闻资讯', 'admin_user_id' => 1, 'grade' => 2, 'parent_id' => 1]
        );
        \App\Channel::create(
            ['name' => '房产财经', 'admin_user_id' => 2, 'grade' => 2, 'parent_id' => 1]
        );
        \App\Channel::create(
            ['name' => '纽澳新闻', 'admin_user_id' => 2, 'grade' => 3, 'parent_id' => 2]
        );
        \App\Channel::create(
            ['name' => '环球报道', 'admin_user_id' => 2, 'grade' => 3, 'parent_id' => 2]
        );
        \App\Channel::create(
            ['name' => '综合', 'admin_user_id' => 2, 'grade' => 4, 'parent_id' => 5]
        );
        \App\Channel::create(
            ['name' => '社会', 'admin_user_id' => 2, 'grade' => 4, 'parent_id' => 5]
        );
    }
}
