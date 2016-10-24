<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleInfo extends Model
{
    public $timestamps = false;

    public function article()
    {
        return $this->belongsTo('\App\Article');
    }

}
