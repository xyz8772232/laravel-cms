<?php

namespace App\Api\Controllers;

use App\Api\Transformers\FavoriteTransformer;
use App\Favorite;
use Dingo\Api\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class FavoriteController
 *
 * @package \App\Api\Controllers
 */
class FavoriteController extends BaseController
{
    public function userList($user_id)
    {
        $rules = ['user_id' => 'required|integer|not_in:0'];
        $validator = Validator::make(['user_id' => $user_id], $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('lang.error_params'));
        }

        $Favorites = Favorite::where('user_id', $user_id)->orderBy('updated_at', 'desc')->paginate(20);
        return $this->paginator($Favorites, new FavoriteTransformer());
    }

    public function isFavorite($user_id, $article_id)
    {
        $rules = [
            'article_id' => 'required|integer|exists:articles,id,state,2,deleted_at,NULL',
            'user_id' => 'required|integer|not_in:0',
        ];
        $validator = Validator::make(['user_id' => $user_id, 'article_id' => $article_id], $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('lang.error_params'));
        }

        $result = Favorite::where([
            ['user_id', $user_id],
            ['article_id', $article_id],
        ])->first();

        if ($result) {
            return [true]; //已收藏
        } else {
            return [false]; //未收藏
        }
    }

    public function store()
    {
        $rules = [
            'article_id' => 'required|integer|exists:articles,id,state,2,deleted_at,NULL',
            'user_id' => 'required|integer|not_in:0',
            'action' => 'in:0,1',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('lang.error_params'));
        }
        $article_id = Input::get('article_id');
        $user_id = Input::get('user_id');
        $action = Input::get('action', 0); //0收藏 1取消

        $favorite = Favorite::where([
            'article_id' => $article_id,
            'user_id' => $user_id,
        ])->first();

        //已收藏删除
        $result = true;
        if ($favorite && $action) {
            $result = $favorite->delete();
        }

        //未收藏收藏
        if (!$favorite && !$action) {
            $result = Favorite::create([
                'article_id' => $article_id,
                'user_id' => $user_id,
            ]);
        }

        if ($result) {
            return '';
        } else {
            return $this->response->errorInternal();
        }
    }
}
