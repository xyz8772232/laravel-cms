<?php
/**
 * Created by PhpStorm.
 * User: xiaotie
 * Date: 16/10/18
 * Time: 13:36
 */

namespace App\Observers;

use App\ArticleLog;
use App\SortLink;
use Encore\Admin\Facades\Admin;
class SortLinkObserver
{
    /**
     * Listen to the Article created event.
     *
     * @param  SortLink  $sortLink
     * @return void
     */
    public function created(SortLink $sortLink)
    {
        ArticleLog::create([
            'operation' => config('article.operation.addSortLink'),
            'article_id' => $sortLink->article_id,
            'admin_user_id' => Admin::user()->id,
        ]);
    }


    /**
     * Listen to the SortLink delete event.
     *
     * @param  SortLink  $sortLink
     * @return void
     */
    public function deleting(SortLink $sortLink)
    {
        ArticleLog::create([
            'operation' => config('article.operation.delSortLink'),
            'article_id' => $sortLink->article_id,
            'admin_user_id' => Admin::user()->id,
        ]);
    }

    /**
     * Listen to the SortLink updated event.
     *
     * @param  SortLink  $sortLink
     * @return void
     */
    public function updated(SortLink $sortLink)
    {
        ArticleLog::create([
            'operation' => config('article.operation.update'),
            'article_id' => $sortLink->article_id,
            'admin_user_id' => Admin::user()->id,
        ]);
    }
}
