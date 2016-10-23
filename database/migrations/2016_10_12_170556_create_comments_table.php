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
            $table->string('content');
            $table->string('ip',50)->nullable();
            $table->unsignedInteger('user_id')->comment('评论者id');
            $table->unsignedInteger('user_nick')->comment('评论者昵称');
            $table->unsignedInteger('reply_to_id')->comment('被评论的id');
            $table->softDeletes();
            $table->timestamps();

            $table->index('article_id');
            $table->index('reply_to_id');
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
