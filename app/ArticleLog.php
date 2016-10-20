<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleLog extends Model
{
    use SoftDeletes;

    protected $fillable = ['article_id', 'operation', 'admin_user_id'];

    public $timestamps = false;

    protected $dates = ['deleted_at'];



    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
        parent::boot();
    }
}
