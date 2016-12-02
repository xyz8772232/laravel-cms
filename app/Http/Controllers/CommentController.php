<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $rules = ['article_id' => 'required|integer|exists:articles,id,state,2,deleted_at,NULL'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect('/');
        }

        $comments = Comment::where('article_id', Input::get('article_id'))->paginate(20);

        return view('wap.article.comment', compact('comments'));


    }

    public function show()
    {

    }
}
