<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    //protected $appends = ['reply_to_user_nick'];

    protected $fillable = ['content', 'reply_to_id', 'article_id', 'user_id', 'user_nick', 'user_avatar'];

    //protected $hidden = ['reply_to_user_nick'];

    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    public function parent()
    {
        return $this->belongsTo('App\Comment', 'reply_to_id');
    }

//    public function getReplyToUserNickAttribute()
//    {
//        if (static::getAttribute('reply_to_id')) {
//            return static::parent()->first()->user_nick;
//        }
//        return null;
//    }
}
