<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BallotAnswer extends Model
{
    use SoftDeletes;

    protected $fillable = ['ballot_id', 'choice_id', 'user_id'];

    public function ballot()
    {
        return $this->belongsTo('App\Ballot');
    }

    public function ballotChoice()
    {
        return $this->belongsTo('App\BallotChoice');
    }

}
