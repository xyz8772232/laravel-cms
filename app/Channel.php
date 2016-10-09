<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function parent()
    {
        $this->belongsTo('Channel', 'parent_id');
    }
}
