<?php
/**
 * Created by PhpStorm.
 * User: xiaotie
 * Date: 16/10/18
 * Time: 13:36
 */

namespace App\Observers;

use App\Article;
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
        if ($article->is_headline) {
            Tool::handleSortLink($article, 'delete');
            $article->is_headline = 0;
            $article->save();
        }

        if ($article->is_slide) {
            Tool::handleSortPhoto($article, 'delete');
        }

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

    public function updated(Article $article) {
        $admin_user_id = Admin::user()->id;
        if ($article->state == 1 && $article->getOriginal('state') == 0) {
            ArticleLog::create([
                'operation' => config('article.operation.audit'),
                'article_id' => $article->id,
                'admin_user_id' => $admin_user_id,
            ]);
        }

        if ($article->channel_id != $article->getOriginal('channel_id')) {
            ArticleLog::create([
                'operation' => config('article.operation.move'),
                'article_id' => $article->id,
                'admin_user_id' => $admin_user_id,
            ]);
        }

    }
}
