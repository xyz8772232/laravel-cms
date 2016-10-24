<?php

namespace App;

use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    use FormAccessible;

    /**
     * 获取文章内容
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function content()
    {
        return $this->hasOne('App\Content');
    }

    /**
     * 文章关键字
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function keywords()
    {
        return $this->belongsToMany('App\Keyword');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * 获取文章作者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo('Encore\Admin\Auth\Database\Administrator');
    }

    /**
     * 获取文章内容
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function articleInfo() {
        return $this->hasOne('App\ArticleInfo');
    }
//    public function getKeywordsAttribute() {
//        return $this->keywords()->pluck('id');
//    }

    public function formKeywordsAttribute() {
        return $this->keywords()->pluck('id')->toArray();
    }

    public function getAuthorNameAttribute() {
        return $this->author->name;
    }

    public function getViewNumAttribute() {
        if ($this->articleInfo) {
            return $this->articleInfo->view_num;
        }
        return 0;
    }

    public function getCommentNumAttribute() {
        if ($this->articleInfo) {
            return $this->articleInfo->comment_num;
        }
        return 0;
    }
}
