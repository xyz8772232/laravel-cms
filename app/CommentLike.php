<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{

    protected $fillable = ['comment_id', 'user_id', 'user_nick', 'user_avatar'];

    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }
}
