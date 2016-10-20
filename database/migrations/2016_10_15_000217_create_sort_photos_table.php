<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSortphotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sort_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->string('title', 255)->nullable();
            $table->string('cover_image', 255)->nullable();
            $table->unsignedInteger('order');
            $table->timestamps();
            $table->softDeletes();


            $table->index('article_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sortphotos');
    }
}
