<?php

namespace App;

use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    use FormAccessible;

    protected $fillable = ['link_id', 'title', 'channel_id', 'author_id', ];

    protected $casts = [
        'is_slide' => 'boolean',
        'is_headline' => 'boolean',
    ];

    protected $dates = [
        'published_at',
    ];

    protected $appends = ['view_num', 'comment_num'];

    protected $hidden = ['view_num', 'comment_num'];

    /**
     * 获取文章内容
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function content()
    {
        return $this->hasOne('App\Content');
    }

    /**
     * 文章关键字
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function keywords()
    {
        return $this->belongsToMany('App\Keyword')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * pk or vote
     */
    public function ballot()
    {
        return $this->hasOne('App\Ballot');
    }

    /**
     * 获取文章作者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('Encore\Admin\Auth\Database\Administrator');
    }

    public function link()
    {
        return $this->belongsTo($this);
    }

    /**
     * 获取文章内容
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function articleInfo()
    {
        return $this->hasOne('App\ArticleInfo');
    }

    public function sortLink()
    {
        return $this->hasOne('App\SortLink');
    }

    public function sortPhoto()
    {
        return $this->hasOne('App\SortPhoto');
    }

    public function scopeAudit($query)
    {
        return $query->where('state', 1);
    }

    public function scopeOnline($query)
    {
        return $query->where('state', 2);
    }
//    public function getKeywordsAttribute() {
//        return $this->keywords()->pluck('id');
//    }

    public function formKeywordsAttribute() {
        return $this->keywords()->pluck('id')->toArray();
    }

    public function getAuthorNameAttribute() {
        if ($this->author) {
            return $this->author->name;
        }
        return '';
    }

    public function getViewNumAttribute() {
        if ($this->articleInfo) {
            return $this->articleInfo->view_num;
        }
        return 0;
    }

    public function getCoverPicAttribute()
    {
        if ($this->attributes['link_id']) {
            return $this->link->cover_pic;
        } else {
            return $this->attributes['cover_pic'];
        }
    }

    public function setCoverPicAttribute($cover_pic)
    {
        if ($this->link) {
            $this->link->cover_pic = $cover_pic;
        } else {
            $this->attributes['cover_pic'] = $cover_pic;
        }
    }

    public function getCommentNumAttribute() {
        if ($this->articleInfo) {
            return $this->articleInfo->comment_num;
        }
        return 0;
    }

    public function getContentAttribute()
    {
        return $this->content()->first()->content;
    }

    public function getIsHeadlineAttribute()
    {
        if ($this->sortLink) {
            return true;
        }
        return false;
    }

    public function getIsSlideAttribute()
    {
        if ($this->sortPhoto) {
            return true;
        }
        return false;
    }

    public function getOnlineAttribute()
    {
        return $this->state == 2;
    }

    public function setOnlineAttribute()
    {
        $this->state = 2;
    }

}
