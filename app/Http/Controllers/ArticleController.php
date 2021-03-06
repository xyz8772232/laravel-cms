<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleStyle;
use App\Comment;
use App\Ballot;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class ArticleController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('appuser')->only('show');
//    }

    public function index()
    {

    }


    public function show($id)
    {
        //$this->getAppUser();
        $article = Article::online()->where('id', $id)->firstOrFail();
        if ($article->link_id) {
            return $this->link($article);
        }
        if ($article->type == 1) {
            return $this->photo($article);
        } else {
            return $this->text($article);
        }
    }

    //文字链接
    private function link(Article $article)
    {
        $link_article = Article::find($article->link_id);
        $link_article->title = $article->title;
        $link_article->channel_id = $article->channel_id;
        if ($link_article->type == 1) {
            return $this->photo($link_article);
        } else {
            return $this->text($link_article);
        }
    }


    //图片文章
    private function photo(Article $article)
    {
        $contentPics = collect(json_decode($article->content, true))->map(function($value) {
            return [
                'img' => image_url($value['img']),
                'title' => $value['title'],
            ];
        })->all();
        return view('wap.article.photo', compact('article', 'contentPics'));

    }

    //普通文章
    private function text(Article $article)
    {
        $article->content = ArticleStyle::articleContent($article->content);
        $comments = Comment::with('parent')->where('article_id', $article->id)->orderBy('created_at', 'desc')->limit(3)->get();
        $ballot = Ballot::with('choices')->where('article_id', $article->id)->first();
//        $userId = self::$appUser['uid'] ?? 0;
//        $username = self::$appUser['username'] ?? '';

        $ballotResult = [];
        if ($ballot) {

            $ballotResult = $ballot->result();
            $ballotConfig = [
                'type' => $ballot->type,
                'max' => $ballot->max_num,
                'agree' => $ballotResult->pluck('approve_num')->all(),
                'agreed' => (bool)array_keys($ballotResult->pluck('approved', 'id')->all(), true),
            ];
        }

        $pageConfig = [
//            'userId' => (int)$userId,
//            'username' => $username,
            'articleId' => $article->id,
            'ballot' => $ballotConfig ?? null,

        ];

        JavaScriptFacade::put([
            'PAGE_CONFIG' => $pageConfig,
        ]);

        return view('wap.article.text', compact('article', 'comments', 'ballot', 'ballotResult'));
    }
}
