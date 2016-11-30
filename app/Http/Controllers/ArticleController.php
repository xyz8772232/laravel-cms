<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index()
    {

    }


    public function show($id)
    {
        $article =  Article::where('state', 2)->where('id', $id)->firstOrFail();
        if ($article->type == 1) {
            return $this->photo($article);
        } else {
            return $this->text($article);
        }
    }

    //图片文章
    private function photo(Article $article)
    {
        return view('wap.article.photo', compact('article'));

    }

    //普通文章
    private function text(Article $article)
    {
        return view('wap.article.text', compact('article'));
    }
}
