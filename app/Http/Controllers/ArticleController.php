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
        return view('wap.article.show', compact('article'));
    }
}
