<?php

namespace App\Api\Controllers;
use App\Api\Transformers\ArticleTransformer;
use App\Article;
use App\Channel;
/**
 * Class ArticleController
 *
 * @package \App\Api\Controllers
 */
class ArticleController extends BaseController
{

    public function index()
    {

    }

    public function channel($channel_id)
    {
        $channelIds = array_merge(Channel::branchIds([], $channel_id), [$channel_id]);
        $articles = Article::with('articleInfo')->where('state', 2)->whereIn('channel_id', $channelIds)->orderBy('published_at', 'desc')->paginate();
        //dd($articles);
        return $this->paginator($articles, new ArticleTransformer());
    }

    public function show($id)
    {
        $article = Article::with('content')->findOrFail($id);
        return $this->response->item($article, new ArticleTransformer());
    }
}
