<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'app_photos';
        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('path')->comment('图片存储路径');
            $table->unsignedTinyInteger('order')->default(0)->comment('顺序');
            $table->unsignedInteger('admin_user_id')->comment('上传人id');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->timestamps();
            $table->softDeletes();


            $table->index('admin_user_id');
            $table->index('status');
            $table->index('order');
        });

        DB::statement("ALTER TABLE `$tableName` comment 'app startup photo'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appphotos');
    }
}
