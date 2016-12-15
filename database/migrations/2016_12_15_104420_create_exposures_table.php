<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExposuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exposures', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('desc', 2000);
            $table->string('link', 255);
            $table->string('contact')->nullable();
            $table->string('uname')->nullable();
            $table->string('wechat')->nullable();
            $table->string('pics', 2000)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exposures');
    }
}
