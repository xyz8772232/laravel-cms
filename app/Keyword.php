<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    use SoftDeletes;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function articles() {
        return $this->belongsToMany('\App\Article')->withTimestamps();
    }


}
