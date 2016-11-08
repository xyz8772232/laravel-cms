<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBallotAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ballot_answers', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ballot_id')->comment('所属ballot');
            $table->string('choice_id')->comment('所属选项');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('ballot_id');
            $table->index('choice_id');
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
        //
    }
}
