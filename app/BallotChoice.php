<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BallotChoice extends Model
{

    protected $fillable = ['ballot_id', 'content'];

    public function ballot()
    {
        return $this->belongsTo('App\Ballot');
    }
}
