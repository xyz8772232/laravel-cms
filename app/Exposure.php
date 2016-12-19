<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exposure extends Model
{

    public $timestamps = false;

    protected $fillable = ['title', 'desc', 'link', 'uname', 'contact', 'pics', 'wechat'];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
        parent::boot();
    }

}
