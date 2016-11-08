<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ballot extends Model
{
    public function choices()
    {
        return $this->hasMany('App/BallotChoice');
    }
}
