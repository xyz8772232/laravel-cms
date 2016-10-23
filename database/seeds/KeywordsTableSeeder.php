<?php

use Illuminate\Database\Seeder;

class KeywordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Keyword::truncate();
        \App\Keyword::create(
            ['name' => '政治', 'admin_user_id' => 1]
        );
        \App\Keyword::create(
            ['name' => '经济', 'admin_user_id' => 2]
        );
        \App\Keyword::create(
            ['name' => '科学', 'admin_user_id' => 2]
        );
        \App\Keyword::create(
            ['name' => '心理', 'admin_user_id' => 2]
        );
        \App\Keyword::create(
            ['name' => '数学', 'admin_user_id' => 2]
        );
        \App\Keyword::create(
            ['name' => '蓝莓', 'admin_user_id' => 2]
        );
        \App\Keyword::create(
            ['name' => '玫瑰', 'admin_user_id' => 2]
        );
    }
}
