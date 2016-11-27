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

    public function getPkAttribute()
    {
        return $this->type == 2;
    }

    public function getVoteAttribute()
    {
        return $this->type == 0 || $this->type == 1;
    }

    public function getSingleVoteAttribute()
    {
        return $this->type == 0;
    }

    public function getMultiVoteAttribute()
    {
        return $this->type == 1;
    }

}
