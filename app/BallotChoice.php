<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BallotChoice extends Model
{
    use SoftDeletes;

    public function ballot()
    {
        return $this->belongsTo('App\Ballot');
    }
}
