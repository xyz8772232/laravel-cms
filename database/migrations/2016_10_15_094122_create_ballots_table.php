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
            $table->boolean('type')->default(0)->comment('0:投票 1:pk');
            $table->unsignedInteger('article_id')->default(0)->comment('0:投票 1:pk');
            $table->unsignedTinyInteger('status')->default(0)->comment('0:进行 1:结束');
            $table->string('content')->comment('投票的内容');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();
            $table->softDeletes();


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
