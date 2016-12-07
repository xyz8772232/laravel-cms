<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBallotChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ballot_choices', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ballot_id')->comment('所属ballot');
            $table->string('content')->comment('选项内容');
            $table->unsignedInteger('approve_num')->comment('赞同数');
            $table->timestamps();

            $table->index('ballot_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ballot_choices');

    }
}
