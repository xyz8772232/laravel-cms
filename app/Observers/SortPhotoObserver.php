<?php
/**
 * Created by PhpStorm.
 * User: xiaotie
 * Date: 16/10/18
 * Time: 13:36
 */

namespace App\Observers;

use App\ArticleLog;
use App\SortPhoto;
use Encore\Admin\Facades\Admin;
class SortPhotoObserver
{
    /**
     * Listen to the Article created event.
     *
     * @param  SortPhoto  $SortPhoto
     * @return void
     */
    public function created(SortPhoto $SortPhoto)
    {
        ArticleLog::create([
            'operation' => config('article.operation.addSortPhoto'),
            'article_id' => $SortPhoto->article_id,
            'admin_user_id' => Admin::user()->id,
        ]);
    }


    /**
     * Listen to the Article delete event.
     *
     * @param  SortPhoto  $SortPhoto
     * @return void
     */
    public function deleting(SortPhoto $SortPhoto)
    {
        ArticleLog::create([
            'operation' => config('article.operation.delSortPhoto'),
            'article_id' => $SortPhoto->article_id,
            'admin_user_id' => Admin::user()->id,
        ]);
    }

    /**
     * Listen to the Article updated event.
     *
     * @param  SortPhoto  $SortPhoto
     * @return void
     */
    public function updated(SortPhoto $SortPhoto)
    {
        ArticleLog::create([
            'operation' => config('article.operation.update'),
            'article_id' => $SortPhoto->article_id,
            'admin_user_id' => Admin::user()->id,
        ]);
    }
}
