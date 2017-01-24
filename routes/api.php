<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Api\Controllers'], function($api) {
        //获取文章评论列表
        $api->get('comments', 'CommentController@index');
        //获取评论的回复列表,点赞数排序
        $api->get('comments/reply_list', 'CommentController@replyList');
        //发表评论
        $api->post('comments', 'CommentController@store');
        //回复评论
        $api->post('comments/reply', 'CommentController@reply');

        //删除评论
        $api->delete('comments', 'CommentController@destroy');

        //点赞
        $api->post('comments/like', 'CommentController@like');

        //点赞人列表
        $api->get('comments/like_users', 'CommentController@likeUsers');


        $api->get('channels', 'ChannelController@index');

        $api->get('articles/channel/{id}', 'ArticleController@channel');
        $api->get('articles/{id}', 'ArticleController@show');

        //投票
        $api->post('ballots/answer', 'BallotController@answer');
        $api->get('ballots/result/{ballot_id}', 'BallotController@result');

        $api->get('sort_links', 'SortLinkController@index');
        $api->get('sort_photos', 'SortPhotoController@index');
        $api->get('sort_links/with_photos', 'SortLinkController@withPhotos');

        $api->get('app_photos', 'AppPhotoController@index');

        //爆料
        $api->post('exposures', 'ExposureController@store');

        //收藏
        $api->post('favorites', 'FavoriteController@store');
        $api->get('favorites/user/{user_id}/article/{article_id}', 'FavoriteController@isFavorite');
        $api->get('favorites/user/{user_id}', 'FavoriteController@userList');

        //上传
        $api->post('upload/image', 'UploadController@image');

        $api->get('test', function(Request $request){
            dd($request->root());
        });

    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
