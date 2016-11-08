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
        $api->get('channels', 'ChannelController@index');
        $api->get('articles/channel/{id}', 'ArticleController@channel');
        $api->get('articles/show/{id}', 'ArticleController@show');
        $api->post('ballots/answer/{id}', 'BallotController@answer');
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
