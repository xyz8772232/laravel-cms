<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{

    protected $fillable = ['article_id', 'user_id'];

    public function article()
    {
        return $this->belongsTo('App\Article');
    }

}
