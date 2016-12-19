<?php
/**
 * Created by PhpStorm.
 * User: xiaotie
 * Date: 16/10/18
 * Time: 13:36
 */

namespace App\Observers;

use App\Article;
use App\ArticleInfo;
use App\ArticleLog;
use App\Tool;
use Encore\Admin\Facades\Admin;

class ArticleObserver
{
    /**
     * Listen to the Article created event.
     *
     * @param  Article  $article
     * @return void
     */
    public function created(Article $article)
    {
        ArticleInfo::create([
            'article_id' => $article->id,
        ]);
        ArticleLog::create([
            'operation' => config('article.operation.create'),
            'article_id' => $article->id,
            'admin_user_id' => Admin::user()->id,
        ]);
    }

    /**
     *监听文章删除事件
     * @param  Article  $article
     * @return void
     */
    public function deleting(Article $article)
    {
        Tool::handleSortLink($article, 'delete');
        Tool::handleSortPhoto($article, 'delete');
    }

    /**
     * Listen to the Article deleted event.
     *
     * @param  Article  $article
     * @return void
     */
    public function deleted(Article $article)
    {
        ArticleLog::create([
            'operation' => config('article.operation.delete'),
            'article_id' => $article->id,
            'admin_user_id' => Admin::user()->id,
        ]);
    }

    /**
     * Listen to the Article updated event.
     *
     * @param  Article  $article
     * @return void
     */
    public function updated(Article $article)
    {
        $admin_user_id = Admin::user()->id;
        if ($article->state == 1 && $article->getOriginal('state') == 0) {
            $operation = config('article.operation.audit');
        } elseif ($article->state == 2 && $article->getOriginal('state') != 2) {
            $operation = config('article.operation.pass');
        } elseif ($article->getOriginal('state') == 2 && $article->state != 2) {
            $operation = config('article.operation.outline');
        }
        elseif ($article->channel_id != $article->getOriginal('channel_id')){
            $operation = config('article.operation.move');
        } else {
            $operation = config('article.operation.update');
        }
        ArticleLog::create([
            'operation' => $operation,
            'article_id' => $article->id,
            'admin_user_id' => $admin_user_id,
        ]);
    }
}
