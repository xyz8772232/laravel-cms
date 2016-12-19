<?php

namespace App\Api\Controllers;

use App\Api\Transformers\CommentTransformer;
use App\Comment;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Request;
/**
 * Class CommentController
 *
 * @package \App\Api\Controllers
 */
class CommentController extends BaseController
{
    public function index()
    {
        $pageSize = Input::get('pageSize', 20);
        $rules = ['article_id' => 'required|integer|exists:articles,id,state,2,deleted_at,NULL'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return $this->errorNotFound();
        }

        $comments = Comment::with('parent')->where('article_id', Input::get('article_id'))->orderBy('created_at', 'desc')->paginate($pageSize);
        return $this->paginator($comments, new CommentTransformer());

    }

    public function store()
    {
        $rules = [
            'article_id' => 'required|integer|exists:articles,id,state,2,deleted_at,NULL',
            'content' => 'required',
            'user_id' => 'required|integer',
            'user_nick' => 'required',
            'user_avatar' => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('lang.error_params'));
        }
        $article_id = Input::get('article_id');
        $content = Input::get('content');
        $user_id = Input::get('user_id');
        $user_nick = Input::get('user_nick');
        $user_avatar = Input::get('user_avatar', '');
        $reply_to_id = (int)Input::get('reply_to_id', 0);

        $result = Comment::create([
            'article_id' => $article_id,
            'content' => $content,
            'ip' => Request::ip(),
            'user_id' => $user_id,
            'user_nick' => $user_nick,
            'user_avatar' => $user_avatar,
            'reply_to_id' => $reply_to_id,
        ]);
        if ($result) {
		return $result;
            //return $this->response->created(null, $result);
        } else {
            return $this->response->errorInternal();
        }
    }
}
