<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatermarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watermarks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path')->comment('图片存储路径');
            $table->unsignedInteger('admin_user_id')->comment('上传人id');
            $table->unsignedTinyInteger('status')->comment('状态');
            $table->timestamps();

            $table->index('admin_user_id');
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
        Schema::dropIfExists('watermarks');
    }
}
