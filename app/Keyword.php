<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    use SoftDeletes;

    /**
     * 需要被转换成日期的属性。
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['name', 'admin_user_id'];

    public function articles() {
        return $this->belongsToMany('\App\Article')->withTimestamps();
    }
}
