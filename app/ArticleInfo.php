<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleInfo extends Model
{
    protected $primaryKey = 'article_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['article_id'];
    protected $guarded = ['view_num', 'comment_num'];

    public function article()
    {
        return $this->belongsTo('\App\Article');
    }

}
