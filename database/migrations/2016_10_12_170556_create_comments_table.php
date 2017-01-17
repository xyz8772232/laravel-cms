<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->string('content', 2000)->charset('utf8mb4')->collate('utf8mb4_unicode_ci');
            //$table->string('content');
            $table->ipAddress('ip')->nullable();
            $table->unsignedInteger('user_id')->comment('评论者id');
            $table->string('user_nick')->comment('评论者昵称');
            $table->unsignedInteger('like_num')->default(0)->comment('点赞数');
            $table->string('user_avatar', 255)->comment('评论者头像');
            $table->unsignedInteger('reply_to_id')->comment('被评论的id');
            $table->unsignedTinyInteger('blocked')->default(0)->comment('是否被屏蔽');
            $table->timestamps();

            $table->index('article_id');
            $table->index('reply_to_id');
            $table->index('blocked');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
