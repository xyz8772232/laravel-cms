<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operation')->comment('操作');
            $table->unsignedInteger('admin_user_id');
            $table->unsignedInteger('article_id');
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();

            $table->index('operation');
            $table->index('admin_user_id');
            $table->index('article_id');
            $table->index('created_at');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syslogs');
    }
}
