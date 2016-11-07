<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');

//频道管理相关
$router->get('channels/tree/{id?}', 'ChannelController@tree');
$router->post('channels/save', 'ChannelController@save');
$router->resource('channels', ChannelController::class);

//文章管理相关
$router->get('articles/channel/{id}', 'ArticleController@channel');
$router->post('articles/change/{id}', 'ArticleController@change');
$router->post('link/{id}', 'ArticleController@link');
$router->resource('articles', ArticleController::class);

$router->resource('logs', ArticleLogController::class);
$router->resource('keywords', KeywordController::class);

//Route::group([
//    'prefix'        => config('admin.prefix'),
//    'namespace'     => Admin::controllerNamespace(),
//    'middleware'    => ['web', 'admin.auth', 'admin.pjax'],
//], function ($router) {
//    $router->post('comments/block/{$id}', 'CommentController@block')->name('comments.block');
//});
$router->post('comments/block/{$id}',['uses' => 'CommentController@block' ,'as' => 'comments.block']);

$router->resource('comments', CommentController::class);
$router->resource('ballots', BallotController::class);

$router->resource('photos', PhotoController::class);
$router->get('watermarks', 'WatermarkController@index');
$router->post('watermarks/save', 'WatermarkController@save');
$router->get('watermarks/create', 'WatermarkController@create');


$router->resource('sort_links', SortLinkController::class);
$router->resource('sort_photos', SortPhotoController::class);

$router->resource('app_photos', AppPhotoController::class);
$router->resource('app_messages', AppMessageController::class);
