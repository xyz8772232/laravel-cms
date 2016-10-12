<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);
            $table->unsignedTinyInteger('grade')->default(0)->comment('频道等级,共四级');
            $table->unsignedInteger('parent_id')->default(0);
            $table->softDeletes();
            $table->timestamps();

            //index
            $table->index('grade');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
