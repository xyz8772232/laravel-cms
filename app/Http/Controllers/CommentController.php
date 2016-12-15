<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class CommentController extends Controller
{
    public function index()
    {
        //$this->getAppUser();
        $rules = ['article_id' => 'required|integer|exists:articles,id,state,2,deleted_at,NULL'];
        $article_id = (int)Input::get('article_id', 0);
        $validator = Validator::make(['article_id' => $article_id], $rules);
        if ($validator->fails()) {
            return back();
        }

        $comments = Comment::with('parent')->where('article_id', $article_id)->paginate(20);

        $userId = self::$appUser['uid'] ?? 0;
        $username = self::$appUser['username'] ?? '';

        $pageConfig = [
            'userId' => (int)$userId,
            'username' => $username,
            'articleId' => $article_id,
        ];

        JavaScriptFacade::put([
            'PAGE_CONFIG' => $pageConfig,
        ]);

        return view('wap.article.comment', compact('comments', 'article_id'));


    }

    public function show()
    {

    }
}
