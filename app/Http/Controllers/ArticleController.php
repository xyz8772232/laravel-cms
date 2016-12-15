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
        $article =  Article::online()->where('id', $id)->firstOrFail();
        if ($article->type == 1) {
            return $this->photo($article);
        } else {
            return $this->text($article);
        }
    }

    //图片文章
    private function photo(Article $article)
    {
        $contentPics = collect(json_decode($article->content, true))->map(function($value) {
            return [
                'img' => cms_local_to_web($value['img']),
                'title' => $value['title'],
            ];
        })->all();
        $commentNum = Comment::where('article_id', $article->id)->count();
        return view('wap.article.photo', compact('article', 'contentPics', 'commentNum'));

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
