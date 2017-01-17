<?php

namespace App\Api\Controllers;

use App\Api\Transformers\CommentTransformer;
use App\Comment;
use App\CommentLike;
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

        $comments = Comment::where([
            ['article_id', '=', Input::get('article_id')],
            ['reply_to_id', '=', 0],
            ['blocked', '=', 0],
            ])->orderBy('created_at', 'desc')->paginate($pageSize);

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

        $result = Comment::create([
            'article_id' => $article_id,
            'content' => $content,
            'ip' => Request::getClientIp(),
            'user_id' => $user_id,
            'user_nick' => $user_nick,
            'user_avatar' => $user_avatar,
        ]);
        if ($result) {
		return $result;
            //return $this->response->created(null, $result);
        } else {
            return $this->response->errorInternal();
        }
    }

    public function like()
    {
        $rules = [
            'comment_id' => 'required|integer|exists:comments,id,blocked,0',
            'user_id' => 'required|integer',
            'user_nick' => 'required',
            'user_avatar' => 'required',
        ];

        $comment_id = Input::get('comment_id');
        $user_id = Input::get('user_id', 0);
        $user_nick = Input::get('user_nick');
        $user_avatar = Input::get('user_avatar', '');

        $validator = Validator::make(
            ['comment_id' => $comment_id, 'user_id' => $user_id, 'user_nick' => $user_nick, 'user_avatar' => $user_avatar]
            , $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('lang.error_params'));
        }

        $result = CommentLike::create(['comment_id' => $comment_id, 'user_id' => $user_id]);

        if ($result) {
            return $this->response->created();
        }
        return $this->response->errorInternal();
    }

    //评论的回复列表
    public function replyList()
    {
        $pageSize = Input::get('pageSize', 20);
        $rules = ['comment_id' => 'required|integer|exists:comments,id,blocked,0'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('lang.error_params'));
        }
        $comments = Comment::where([
            ['reply_to_id', '=', Input::get('comment_id')],
            ['blocked', '=', 0],
        ])->orderBy('like_num', 'desc')->paginate($pageSize);

        return $this->paginator($comments, new CommentTransformer());
    }

    //回复评论
    public function reply()
    {
        $rules = [
            'comment_id' => 'required|integer|exists:comments,id,blocked,0',
            'content' => 'required',
            'user_id' => 'required|integer',
            'user_nick' => 'required',
            'user_avatar' => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('lang.error_params'));
        }
        $comment_id = Input::get('comment_id');
        $comment = Comment::find($comment_id);
        $article_id = $comment->article_id;
        $content = Input::get('content');
        $user_id = Input::get('user_id');
        $user_nick = Input::get('user_nick');
        $user_avatar = Input::get('user_avatar', '');

        $result = Comment::create([
            'article_id' => $article_id,
            'content' => $content,
            'ip' => Request::getClientIp(),
            'user_id' => $user_id,
            'user_nick' => $user_nick,
            'user_avatar' => $user_avatar,
            'reply_to_id' => $comment_id,
        ]);
        if ($result) {
            return $result;
        } else {
            return $this->response->errorInternal();
        }
    }
}
