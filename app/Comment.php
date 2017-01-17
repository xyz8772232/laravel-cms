<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $appends = ['reply_num'];

    protected $fillable = ['content', 'reply_to_id', 'article_id', 'user_id', 'user_nick', 'user_avatar'];

    protected $hidden = ['reply_num'];

    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    public function parent()
    {
        return $this->belongsTo('App\Comment', 'reply_to_id');
    }

    public function getReplyNumAttribute()
    {
        return $this->where([
            ['reply_to_id', '=', $this->id],
            ['blocked', '=', 0],
        ])->count();
    }

//    public function getReplyToUserNickAttribute()
//    {
//        if (static::getAttribute('reply_to_id')) {
//            return static::parent()->first()->user_nick;
//        }
//        return null;
//    }
}
