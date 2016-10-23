<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['article_id', 'content'];

    public $timestamps = false;

    public function article()
    {
        return $this->belongsTo('\App\Article');
    }
}
