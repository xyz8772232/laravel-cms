<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = ['content', 'reply_to_id', 'article_id', 'user_id', 'user_nick'];

    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    public function parent()
    {
        return $this->belongsTo('App\Comment', 'reply_to_id');
    }
}
