<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_infos', function (Blueprint $table) {
            $table->unsignedInteger('article_id');
            $table->unsignedBigInteger('view_num')->default(0);
            $table->unsignedBigInteger('comment_num')->default(0);

            $table->primary('article_id');
            $table->index('view_num');
            $table->index('comment_num');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_infos');
    }
}
