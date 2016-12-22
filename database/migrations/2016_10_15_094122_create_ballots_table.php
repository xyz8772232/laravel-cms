<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBallotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'ballots';
        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('type')->default(1)->comment('1:单选 2: 多选 3:PK');
            $table->unsignedTinyInteger('max_num')->default(1)->comment('最多选择数量');
            $table->unsignedInteger('article_id');
            $table->unsignedTinyInteger('status')->default(2)->comment('1:未开始 2:进行中  3:已结束');
            $table->string('title')->comment('投票的标题');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();


            //index
            $table->index('type');
            $table->unique('article_id');
            $table->index('status');
            $table->index('start_at');
            $table->index('end_at');
        });


        DB::statement("ALTER TABLE `$tableName` comment 'vote and pk'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ballots');
    }
}
