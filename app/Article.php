<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    /**
     * 获取文章内容
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
        return $this->hasMany('\App\Comment');
    }
}
