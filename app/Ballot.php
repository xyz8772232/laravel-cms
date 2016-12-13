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

    /**
     * @param int $userId
     *
     * @return mixed
     */
    public function result($userId = 0)
    {
        $choices = static::choices()->get();
        $total = $choices->sum('approve_num');
        $choices = $choices->map(function($choice) use($total, $userId) {
            return [
                'id' => $choice->id,
                'content' => $choice->content,
                'approve_num' => $choice->approve_num,
                'approve_percent' => empty($total) ? '0%' : intval(strval(round($choice->approve_num/$total, 2)*100)).'%',
                'approved' => empty($userId) ? false : BallotAnswer::where('choice_id', $choice->id)->where('user_id', $userId)->exists(),
            ];
        });
        return $choices;
    }

}
