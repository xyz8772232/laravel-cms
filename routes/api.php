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
        $api->get('comments', 'CommentController@index');
        //发表评论
        $api->post('comments', 'CommentController@store');

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

    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
