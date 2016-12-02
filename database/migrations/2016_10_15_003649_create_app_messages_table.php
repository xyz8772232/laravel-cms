<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('admin_user_id')->comment('发布人id');
            $table->string('content', 2000)->charset('utf8mb4')->collate('utf8mb4_unicode_ci');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->timestamps();
            $table->softDeletes();

            $table->index('article_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appmessages');
    }
}
