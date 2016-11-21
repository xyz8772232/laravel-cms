<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ballot extends Model
{
    protected $fillable = ['title', 'article_id', 'max_num', 'type', 'start_at', 'end_at'];

    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    public function choices()
    {
        return $this->hasMany('App\BallotChoice');
    }
}
