<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //id,title,title_font title_color,image,top_grade,is_top,state,keywords,description,author_id,source,content,type,create_at,update_at,auditor_id
        Schema::create('articles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->tinyInteger('state')->default(0)->comment('状态');
            $table->unsignedInteger('link_id')->default(0)->comment('0为正常文章,>0是文字链接对应的文章id');
            $table->tinyInteger('type')->default(0)->comment('0:普通1:图片');
            $table->string('title', 255);
            $table->unsignedInteger('author_id')->default(0)->comment('作者');
            $table->unsignedInteger('auditor_id')->default(0)->comment('审核人');
            $table->unsignedInteger('channel_id')->default(0);
            $table->boolean('title_bold')->default(0)->comment('是否粗体');
            $table->string('title_color', 255)->default('#333333');
            $table->string('subtitle', 255)->default('');
            $table->string('cover_pic', 1000)->nullable()->comment('封面图');
            $table->unsignedtinyInteger('top_grade')->default(0)->comment('置顶级别');
            $table->string('description', 2000)->default('');
            $table->string('source', 255)->default('')->comment('信息来源');
            $table->string('original_url', 255)->default('')->comment('原始链接');
            //$table->boolean('is_headline')->default(0)->comment('头条');
            $table->boolean('is_soft')->default(0)->comment('软广');
            $table->boolean('is_political')->default(0)->comment('政治敏感');
            $table->boolean('is_international')->default(0)->comment('国际');
            $table->boolean('is_important')->default(0)->comment('重要');
            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->timestamps();
            $table->softDeletes();

            //index
            $table->index('state');
            $table->index('link_id');
            $table->index('type');
            $table->index('title');
            $table->index('author_id');
            $table->index('top_grade');
            $table->index('source');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
